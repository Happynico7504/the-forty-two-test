<?php
// Passe diese Werte an deine Serverumgebung an.
// Am besten NICHT ins Git-Repo committen -> siehe .gitignore (config.local.php)

if (file_exists(__DIR__ . '/config.local.php')) {
    require __DIR__ . '/config.local.php';
    return;
}

define('DB_HOST', 'localhost');
define('DB_NAME', 'weltproblem_score');
define('DB_USER', 'db_user');
define('DB_PASS', 'db_password');

// Feste Konstanten der Formel
define('SINN_DES_LEBENS', 42);
define('MAX_ENTRIES', 500);
define('ENTRIES_PER_PAGE', 25);
define('MAX_PAGES', 20);
