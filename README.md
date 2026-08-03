# The Forty Two Test

> Does the meaning of life (42) still outweigh the world's problems? Find it out using this Test!

Ein kleines PHP/MySQL-Tool, das die Formel

```
score = 42 × probleme ÷ schöne_dinge ÷ teilnehmer
```

berechnet und mit dem Sinn des Lebens (42) vergleicht.

## Setup

1. Datenbank anlegen:
   ```
   mysql -u root -p < schema.sql
   ```
2. `config/config.local.php.example` nach `config/config.local.php` kopieren
   und Zugangsdaten eintragen. Diese Datei wird von `.gitignore` ausgeschlossen.
3. Ordner auf einen PHP 8+ Server mit `mysqli`-Erweiterung deployen
   (Apache, Nginx+PHP-FPM, oder lokal per `php -S localhost:8000`).
4. `index.php` im Browser öffnen.

## Funktionsweise

- **index.php** – Eingabeformular (Teilnehmer, Probleme, schöne Dinge)
- **submit.php** – berechnet den Score, speichert den Eintrag, löscht
  bei Überschreiten von 500 Einträgen automatisch die ältesten
- **results.php** – zeigt Einträge paginiert (25/Seite, max. 20 Seiten = 500 Einträge)
- **lang/*.json** – Übersetzungsdateien, automatische Spracherkennung via
  `Accept-Language`-Header, überschreibbar per `?lang=xx`

## Neue Sprache hinzufügen

Einfach `lang/<code>.json` nach dem Schema von `lang/en.json` anlegen
(z. B. `lang/it.json`, `lang/ja.json`). Fehlende Schlüssel fallen
automatisch auf Englisch zurück.

## Hinweis zur "jeder möglichen Systemsprache"

Der Browser sendet nur seine eingestellte Sprache im `Accept-Language`-Header –
eine 1:1-Abdeckung aller ~7000 Sprachen der Welt ist nicht realistisch,
aber das System ist beliebig erweiterbar: jede zusätzliche JSON-Datei
schaltet sofort eine weitere Sprache frei.
