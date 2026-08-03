<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/lang.php';

$i18n = load_translations();
$langs = available_languages();

$db = get_db();

$totalResult = $db->query('SELECT COUNT(*) AS cnt FROM entries');
$total = (int)$totalResult->fetch_assoc()['cnt'];

$totalPages = max(1, min(MAX_PAGES, (int)ceil($total / ENTRIES_PER_PAGE)));
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, min($totalPages, $page));
$offset = ($page - 1) * ENTRIES_PER_PAGE;

$stmt = $db->prepare(
    'SELECT id, participants, problems, nice_things, score, meaning_wins, created_at
     FROM entries
     ORDER BY created_at DESC, id DESC
     LIMIT ? OFFSET ?'
);
$limit = ENTRIES_PER_PAGE;
$stmt->bind_param('ii', $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
$entries = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($i18n['lang']) ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars(t($i18n, 'site_title')) ?></title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
  <div class="title-block">
    <h1><?= htmlspecialchars(t($i18n, 'site_title')) ?></h1>
    <span class="subtitle"><?= htmlspecialchars(t($i18n, 'site_subtitle')) ?></span>
  </div>
  <nav>
    <a href="index.php"><?= htmlspecialchars(t($i18n, 'nav_home')) ?></a>
    <a href="results.php" class="active"><?= htmlspecialchars(t($i18n, 'nav_results')) ?></a>
  </nav>
  <form class="lang-switch" method="get">
    <input type="hidden" name="page" value="<?= (int)$page ?>">
    <select name="lang" onchange="this.form.submit()">
      <?php foreach ($langs as $code): ?>
        <option value="<?= htmlspecialchars($code) ?>" <?= $code === $i18n['lang'] ? 'selected' : '' ?>>
          <?= strtoupper(htmlspecialchars($code)) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </form>
</header>

<main class="container">
  <h2><?= htmlspecialchars(t($i18n, 'table_heading')) ?></h2>

  <?php if (empty($entries)): ?>
    <p><?= htmlspecialchars(t($i18n, 'no_entries')) ?></p>
  <?php else: ?>
    <table class="results-table">
      <thead>
        <tr>
          <th><?= htmlspecialchars(t($i18n, 'col_id')) ?></th>
          <th><?= htmlspecialchars(t($i18n, 'col_participants')) ?></th>
          <th><?= htmlspecialchars(t($i18n, 'col_problems')) ?></th>
          <th><?= htmlspecialchars(t($i18n, 'col_nice_things')) ?></th>
          <th><?= htmlspecialchars(t($i18n, 'col_score')) ?></th>
          <th><?= htmlspecialchars(t($i18n, 'col_verdict')) ?></th>
          <th><?= htmlspecialchars(t($i18n, 'col_date')) ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($entries as $e): ?>
          <tr class="<?= $e['meaning_wins'] ? 'row-meaning' : 'row-problems' ?>">
            <td><?= (int)$e['id'] ?></td>
            <td><?= (int)$e['participants'] ?></td>
            <td><?= (int)$e['problems'] ?></td>
            <td><?= (int)$e['nice_things'] ?></td>
            <td><?= number_format((float)$e['score'], 2) ?></td>
            <td>
              <?= $e['meaning_wins']
                  ? htmlspecialchars(t($i18n, 'verdict_meaning'))
                  : htmlspecialchars(t($i18n, 'verdict_problems')) ?>
            </td>
            <td><?= htmlspecialchars($e['created_at']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="pagination">
      <a href="results.php?page=<?= max(1, $page - 1) ?>&lang=<?= urlencode($i18n['lang']) ?>"
         class="<?= $page <= 1 ? 'disabled' : '' ?>">&laquo; <?= htmlspecialchars(t($i18n, 'prev')) ?></a>

      <span><?= htmlspecialchars(t($i18n, 'page_label')) ?> <?= $page ?> / <?= $totalPages ?></span>

      <a href="results.php?page=<?= min($totalPages, $page + 1) ?>&lang=<?= urlencode($i18n['lang']) ?>"
         class="<?= $page >= $totalPages ? 'disabled' : '' ?>"><?= htmlspecialchars(t($i18n, 'next')) ?> &raquo;</a>
    </div>
  <?php endif; ?>
</main>
</body>
</html>
