<?php
/**
 * Einfaches i18n-System.
 * - Erkennt die bevorzugte Sprache des Browsers/Systems über den
 *   Accept-Language Header (das ist das, was "Systemsprache" im Web bedeutet,
 *   da PHP die tatsächliche OS-Sprache des Clients nicht auslesen kann).
 * - Erlaubt Überschreiben per ?lang=xx in der URL.
 * - Fällt zurück auf Englisch, wenn keine passende Übersetzungsdatei existiert.
 *
 * Neue Sprachen hinzufügen: einfach lang/<code>.json anlegen,
 * z.B. lang/it.json, lang/ja.json, lang/pt.json ...
 */

function detect_language(): string {
    if (isset($_GET['lang'])) {
        $requested = preg_replace('/[^a-z]/', '', strtolower($_GET['lang']));
        if ($requested && file_exists(__DIR__ . "/lang/{$requested}.json")) {
            setcookie('lang', $requested, time() + 60 * 60 * 24 * 365, '/');
            return $requested;
        }
    }

    if (isset($_COOKIE['lang']) && file_exists(__DIR__ . "/lang/{$_COOKIE['lang']}.json")) {
        return $_COOKIE['lang'];
    }

    $header = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
    // Beispiel-Header: "de-DE,de;q=0.9,en-US;q=0.8,en;q=0.7"
    $parts = explode(',', $header);
    foreach ($parts as $part) {
        $code = strtolower(substr(trim(explode(';', $part)[0]), 0, 2));
        if ($code && file_exists(__DIR__ . "/lang/{$code}.json")) {
            return $code;
        }
    }

    return 'en';
}

function load_translations(): array {
    $lang = detect_language();
    $path = __DIR__ . "/lang/{$lang}.json";
    $fallback = __DIR__ . "/lang/en.json";

    $translations = json_decode(file_get_contents($fallback), true) ?: [];
    if ($lang !== 'en' && file_exists($path)) {
        $override = json_decode(file_get_contents($path), true) ?: [];
        $translations = array_merge($translations, $override);
    }

    return ['lang' => $lang, 'strings' => $translations];
}

function t(array $i18n, string $key): string {
    return $i18n['strings'][$key] ?? $key;
}

function available_languages(): array {
    $files = glob(__DIR__ . '/lang/*.json');
    $codes = [];
    foreach ($files as $f) {
        $codes[] = basename($f, '.json');
    }
    sort($codes);
    return $codes;
}
