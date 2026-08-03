<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/lang.php';

$participants = filter_input(INPUT_POST, 'participants', FILTER_VALIDATE_INT);
$problems     = filter_input(INPUT_POST, 'problems', FILTER_VALIDATE_INT);
$nice_things  = filter_input(INPUT_POST, 'nice_things', FILTER_VALIDATE_INT);

if ($participants === false || $participants === null || $participants < 1 ||
    $problems === false || $problems === null || $problems < 0 ||
    $nice_things === false || $nice_things === null || $nice_things < 1) {
    header('Location: index.php?error=invalid');
    exit;
}

$score = (SINN_DES_LEBENS * $problems) / $nice_things / $participants;
$meaning_wins = SINN_DES_LEBENS > $score ? 1 : 0;

$db = get_db();

$stmt = $db->prepare(
    'INSERT INTO entries (participants, problems, nice_things, score, meaning_wins)
     VALUES (?, ?, ?, ?, ?)'
);
$stmt->bind_param('iiidi', $participants, $problems, $nice_things, $score, $meaning_wins);
$stmt->execute();
$stmt->close();

// Älteste Einträge löschen, wenn wir über MAX_ENTRIES liegen
$countResult = $db->query('SELECT COUNT(*) AS cnt FROM entries');
$count = (int)$countResult->fetch_assoc()['cnt'];

if ($count > MAX_ENTRIES) {
    $overflow = $count - MAX_ENTRIES;
    $del = $db->prepare(
        'DELETE FROM entries ORDER BY created_at ASC, id ASC LIMIT ?'
    );
    $del->bind_param('i', $overflow);
    $del->execute();
    $del->close();
}

header('Location: results.php?page=1');
exit;
