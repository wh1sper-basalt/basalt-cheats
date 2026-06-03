<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/inc/mobile-guard.php';

require dirname(__DIR__) . '/connect_practice13.php';

$groupSlug = trim((string) ($_GET['group'] ?? ''));
$practice13Title = $groupSlug !== '' ? 'Альбомы исполнителя' : 'Альбомы';

if ($groupSlug !== '') {
    $stmt = $mysqli_practice13->prepare(
        'SELECT a.id, a.title, a.release_year, a.release_country, a.cover_path, g.name AS group_name
         FROM music_albums a
         INNER JOIN music_groups g ON g.id = a.group_id
         WHERE g.slug = ?
         ORDER BY a.release_year ASC'
    );
    $stmt->bind_param('s', $groupSlug);
    $stmt->execute();
    $res = $stmt->get_result();
} else {
    $res = $mysqli_practice13->query(
        'SELECT a.id, a.title, a.release_year, a.release_country, a.cover_path, g.name AS group_name
         FROM music_albums a
         INNER JOIN music_groups g ON g.id = a.group_id
         ORDER BY g.name ASC, a.release_year ASC'
    );
}

require __DIR__ . '/inc/layout-top.php';
?>
<h1><?= htmlspecialchars($practice13Title, ENT_QUOTES, 'UTF-8') ?></h1>
<?php if ($groupSlug !== '') { ?>
  <p><a href="index.php">← Все группы</a></p>
<?php } ?>
<table>
  <thead>
    <tr>
      <th></th>
      <th>Название</th>
      <th>Исполнитель</th>
      <th>Год</th>
      <th>Страна</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $cover = (string) ($row['cover_path'] ?? '');
            ?>
            <tr>
              <td><?php if ($cover !== '') { ?><img class="cover" src="<?= htmlspecialchars($cover, ENT_QUOTES, 'UTF-8') ?>" alt=""><?php } ?></td>
              <td><?= htmlspecialchars((string) $row['title'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string) $row['group_name'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= (int) $row['release_year'] ?></td>
              <td><?= htmlspecialchars((string) $row['release_country'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><a href="tracks.php?album=<?= (int) $row['id'] ?>">Треки</a></td>
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
