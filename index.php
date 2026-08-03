<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

require_once __DIR__ . '/lang.php';
$i18n = load_translations();
$langs = available_languages();
$error = $_GET['error'] ?? null;
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
    <a href="index.php" class="active"><?= htmlspecialchars(t($i18n, 'nav_home')) ?></a>
    <a href="results.php"><?= htmlspecialchars(t($i18n, 'nav_results')) ?></a>
  </nav>
  <form class="lang-switch" method="get">
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
  <section class="explain">
    <h2><?= htmlspecialchars(t($i18n, 'explain_heading')) ?></h2>
    <p><?= htmlspecialchars(t($i18n, 'explain_p1')) ?></p>
    <p><?= htmlspecialchars(t($i18n, 'explain_p2')) ?></p>
    <p><?= htmlspecialchars(t($i18n, 'explain_p3')) ?></p>
    <p class="formula-note"><?= htmlspecialchars(t($i18n, 'formula_note')) ?></p>
  </section>

  <?php if ($error === 'invalid'): ?>
    <div class="alert"><?= htmlspecialchars(t($i18n, 'error_invalid')) ?></div>
  <?php endif; ?>

  <form action="submit.php" method="post" class="entry-form">
    <label for="participants"><?= htmlspecialchars(t($i18n, 'label_participants')) ?></label>
    <input type="number" min="1" step="1" name="participants" id="participants" required>

    <label for="problems"><?= htmlspecialchars(t($i18n, 'label_problems')) ?></label>
    <input type="number" min="0" step="1" name="problems" id="problems" required>

    <label for="nice_things"><?= htmlspecialchars(t($i18n, 'label_nice_things')) ?></label>
    <input type="number" min="1" step="1" name="nice_things" id="nice_things" required>

    <button type="submit"><?= htmlspecialchars(t($i18n, 'submit_button')) ?></button>
  </form>
</main>

<script src="assets/script.js"></script>
</body>
</html>
