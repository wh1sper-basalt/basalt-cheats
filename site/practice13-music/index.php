<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/inc/mobile-guard.php';

require dirname(__DIR__) . '/connect_practice13.php';

$practice13Title = 'Группы';
$res = $mysqli_practice13->query(
    'SELECT id, slug, name, country, founded_year, style FROM music_groups ORDER BY name ASC'
);
require __DIR__ . '/inc/layout-top.php';
?>
<h1>Группы</h1>
<table>
  <thead>
    <tr>
      <th>Название</th>
      <th>Страна</th>
      <th>Год</th>
      <th>Стиль</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            ?>
            <tr>
              <td><?= htmlspecialchars((string) $row['name'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string) $row['country'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= (int) $row['founded_year'] ?></td>
              <td><?= htmlspecialchars((string) $row['style'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><a href="albums.php?group=<?= urlencode((string) $row['slug']) ?>">подробнее</a></td>
            </tr>
            <?php
        }
        $res->free();
    }
    ?>
  </tbody>
</table>
<?php require __DIR__ . '/inc/layout-bottom.php'; ?>
