<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/inc/mobile-guard.php';

require dirname(__DIR__) . '/connect_practice13.php';

$albumId = (int) ($_GET['album'] ?? 0);
$practice13Title = 'Треки';

if ($albumId > 0) {
    $head = $mysqli_practice13->prepare(
        'SELECT a.title AS album_title, g.name AS group_name
         FROM music_albums a INNER JOIN music_groups g ON g.id = a.group_id WHERE a.id = ? LIMIT 1'
    );
    $head->bind_param('i', $albumId);
    $head->execute();
    $albumMeta = $head->get_result()->fetch_assoc();
    $head->close();

    $stmt = $mysqli_practice13->prepare(
        'SELECT id, title, note FROM music_tracks WHERE album_id = ? ORDER BY id ASC'
    );
    $stmt->bind_param('i', $albumId);
    $stmt->execute();
    $res = $stmt->get_result();
    $practice13Title = 'Треки альбома';
} else {
    $albumMeta = null;
    $res = $mysqli_practice13->query(
        'SELECT t.id, t.title, t.note, a.title AS album_title, g.name AS group_name
         FROM music_tracks t
         INNER JOIN music_albums a ON a.id = t.album_id
         INNER JOIN music_groups g ON g.id = a.group_id
         ORDER BY g.name, a.title, t.id'
    );
}

require __DIR__ . '/inc/layout-top.php';
?>
<h1><?= htmlspecialchars($practice13Title, ENT_QUOTES, 'UTF-8') ?></h1>
<?php if ($albumMeta) { ?>
  <p class="muted">Альбом: <strong><?= htmlspecialchars((string) $albumMeta['album_title'], ENT_QUOTES, 'UTF-8') ?></strong>
    — <?= htmlspecialchars((string) $albumMeta['group_name'], ENT_QUOTES, 'UTF-8') ?></p>
  <p><a href="albums.php">← Альбомы</a></p>
<?php } ?>
<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Название</th>
      <?php if (!$albumMeta) { ?><th>Альбом</th><th>Группа</th><?php } ?>
      <th>Примечание</th>
    </tr>
  </thead>
  <tbody>
    <?php
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            ?>
            <tr>
              <td><?= (int) $row['id'] ?></td>
              <td><?= htmlspecialchars((string) $row['title'], ENT_QUOTES, 'UTF-8') ?></td>
              <?php if (!$albumMeta) { ?>
                <td><?= htmlspecialchars((string) $row['album_title'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) $row['group_name'], ENT_QUOTES, 'UTF-8') ?></td>
              <?php } ?>
              <td><?= htmlspecialchars((string) ($row['note'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
            <?php
        }
        $res->free();
    }
    if (isset($stmt)) {
        $stmt->close();
    }
    ?>
  </tbody>
</table>
<?php require __DIR__ . '/inc/layout-bottom.php'; ?>
