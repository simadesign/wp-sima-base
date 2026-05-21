# SiMa Base

**SiMa Base** ist ein WordPress-Basis-Plugin für SiMa-Projekte. Es stellt Hilfsfunktionen für die Entwicklung individueller WordPress-Themes bereit und ergänzt WordPress um grundlegende Sicherheits- und Komfortfunktionen.

Das Plugin eignet sich besonders als Fundament für Custom Themes in Kundenprojekten.

---

## Voraussetzungen

- WordPress
- PHP mit Composer-Autoloading
- Optional: ACF, WooCommerce, Bootstrap, CountUp

---

## Zugriff auf SiMa Base

Das Plugin stellt die globale Helper-Funktion `sima()` bereit.

```php
sima();
```

Darüber können die wichtigsten Bereiche des Plugins angesprochen werden:

```php
sima()->plugin();
sima()->theme();
sima()->security();
```

---

## Grundbeispiel

```php
<?php

if (!defined('ABSPATH')) {
    exit;
}

if (function_exists('sima')) {

    /*
     * Theme-Anpassungen aktivieren
     */
    sima()->theme()
        ->useTitleBuilder()
        ->useCustomExcerptLength(32);

    /*
     * Menüs registrieren
     */
    sima()->theme()
        ->menu('main', 'Primary menu')
        ->menu('footer', 'Footer menu');

    /*
     * Frontend-Scripts und Styles laden
     */
    sima()->theme()->onLoadFrontend(function(\SimaBase\Frontend\Theme $theme) {
        $theme
            ->styles()->useBootstrapGrid()
            ->styles()->useBootstrapUtils()
            ->styles()->useUtils()
            ->useThemeStyle('style', '/style.css', '1.1.2');
    });

    /*
     * Social-Media-Optionen aktivieren
     */
    sima()->theme()->getSocial()
        ->addInstagram()
        ->addFacebook();

    /*
     * Customizer-Einstellungen registrieren
     */
    sima()->theme()->getCustomizer()->build(function(\SimaBase\Admin\Customizer\Panel $panel) {
        $panel->addSection('footer', 'Footer')->setPriority(2)
            ->addTextareaSetting('footer_text_left', 'Footer Text Left')
            ->addTextareaSetting('footer_text_right', 'Footer Text Right');
    });

}
```

---

## Theme-Funktionen

Alle Theme-Funktionen sind über `sima()->theme()` erreichbar.

### Title Builder aktivieren

```php
sima()->theme()->useTitleBuilder();
```

Aktiviert einen einfachen Title Builder über den WordPress-Filter `wp_title`.

---

### Excerpt-Länge anpassen

```php
sima()->theme()->useCustomExcerptLength(32);
```

Setzt die Länge von WordPress-Excerpts.

---

### Menüs registrieren

```php
sima()->theme()
    ->menu('main', 'Primary menu')
    ->menu('footer', 'Footer menu');
```

Registriert WordPress-Menüs über `register_nav_menus()`.

Sobald mindestens ein Menü registriert wird, aktiviert SiMa Base automatisch Theme-Support für Menüs.

---

### Frontend-Code nur im Frontend ausführen

```php
sima()->theme()->onLoadFrontend(function(\SimaBase\Frontend\Theme $theme) {
    // Nur im Frontend, nicht im Adminbereich oder auf der Login-Seite
});
```

Der Callback wird auf `init` ausgeführt, jedoch nur wenn:

- der aktuelle Request nicht im Adminbereich ist
- die aktuelle Seite nicht `wp-login.php` oder `wp-register.php` ist

---

## Styles laden

Styles können direkt über `sima()->theme()` oder über den Style Helper geladen werden.

### Eigenes Stylesheet laden

```php
sima()->theme()->useStyle(
    'custom-style',
    get_template_directory_uri() . '/assets/css/custom.css',
    '1.0.0'
);
```

### Stylesheet aus dem aktiven Theme laden

```php
sima()->theme()->useThemeStyle('style', '/style.css', '1.0.0');
```

Der Pfad wird relativ zum aktiven Theme-Verzeichnis aufgelöst.

---

### Verfügbare Style Helper

```php
sima()->theme()->styles()->useUtils();
```

Lädt die SiMa-Base-eigene Utility-CSS-Datei:

```text
/includes/css/style.css
```

```php
sima()->theme()->styles()->useBootstrapGrid();
```

Lädt Bootstrap Grid aus dem Plugin-Verzeichnis.

```php
sima()->theme()->styles()->useBootstrapUtils();
```

Lädt Bootstrap Utilities aus dem Plugin-Verzeichnis.

---

## Scripts laden

Scripts können direkt über `sima()->theme()` oder über den Script Helper geladen werden.

### Eigenes Script laden

```php
sima()->theme()->useScript(
    'custom-script',
    get_template_directory_uri() . '/assets/js/custom.js',
    '1.0.0'
);
```

### Script aus dem aktiven Theme laden

```php
sima()->theme()->useThemeScript('theme-script', '/assets/js/theme.js', '1.0.0');
```

---

### Verfügbare Script Helper

```php
sima()->theme()->scripts()->useJquery();
```

Lädt WordPress-jQuery und lokalisiert `ajaxurl`.

```php
sima()->theme()->scripts()->useScrollFlow();
```

Lädt das Script:

```text
/includes/js/ScrollFlow.js
```

```php
sima()->theme()->scripts()->useCountUp();
```

Lädt CountUp aus dem Plugin-Verzeichnis.

---

## Theme-Support

SiMa Base aktiviert automatisch folgende WordPress-Theme-Features:

```php
add_theme_support('title-tag');
add_theme_support('gallery');
add_theme_support('post-thumbnails');
```

Zusätzlich können weitere Features aktiviert werden.

---

### ACF Google Maps API Key setzen

```php
sima()->theme()->useAcfGoogleMaps('GOOGLE_MAPS_API_KEY');
```

Setzt den API-Key für das ACF Google Maps Field.

---

### WooCommerce-Support aktivieren

```php
sima()->theme()->useWoocommerce();
```

Aktiviert WooCommerce-Support für das Theme.

Zusätzlich wird:

- der WooCommerce-Seitentitel deaktiviert
- die Produkt-Archiv-Bildgröße auf `large` gesetzt

---

## Customizer

SiMa Base bietet einen einfachen Wrapper für den WordPress Customizer.

Der Standard-Panel-Name ist:

```text
SiMa Theme
```

Die Standard-Panel-ID lautet:

```text
simadesign
```

---

### Customizer-Bereich erstellen

```php
sima()->theme()->getCustomizer()->build(function(\SimaBase\Admin\Customizer\Panel $panel) {
    $panel->addSection('footer', 'Footer')
        ->addTextSetting('footer_title', 'Footer Title')
        ->addTextareaSetting('footer_text', 'Footer Text');
});
```

---

### Panel konfigurieren

```php
$panel
    ->setPriority(2)
    ->setDescription('Theme Einstellungen');
```

---

### Section konfigurieren

```php
$panel->addSection('footer', 'Footer')
    ->setPriority(2)
    ->setDescription('Footer Einstellungen');
```

---

### Textfeld hinzufügen

```php
$section->addTextSetting('footer_title', 'Footer Title');
```

---

### Textarea hinzufügen

```php
$section->addTextareaSetting('footer_text', 'Footer Text');
```

---

### Eigene Setting-Argumente übergeben

```php
$section->addTextSetting('phone', 'Telefonnummer', [
    'description' => 'Telefonnummer für den Footer',
]);
```

---

## Social Media Optionen

SiMa Base kann Social-Media-Felder im Customizer registrieren.

### Plattformen aktivieren

```php
sima()->theme()->getSocial()
    ->addInstagram()
    ->addFacebook()
    ->addLinkedIn()
    ->addYouTube();
```

---

### Verfügbare Plattformen

```php
addInstagram()
addFacebook()
addTikTok()
addLinkedIn()
addYouTube()
addX()
addThreads()
```

---

### Eigene Plattform hinzufügen

```php
sima()->theme()->getSocial()
    ->addPlatform('pinterest', 'Pinterest');
```

Dadurch wird im Customizer ein Feld mit der ID `social_pinterest` erstellt.

---

### Social Media Werte abrufen

```php
$instagram = sima()->theme()->getSocial()->getInstagram();
$facebook  = sima()->theme()->getSocial()->getFacebook();
$linkedin  = sima()->theme()->getSocial()->getLinkedIn();
```

---

### Verfügbare Getter

```php
getInstagram()
getFacebook()
getTikTok()
getLinkedIn()
getYouTube()
getX()
getThreads()
```

---

### Eigenen Social-Wert abrufen

```php
$pinterest = sima()->theme()->getSocial()->getValue('pinterest');
```

---

## Sicherheit

Die Sicherheitsfunktionen sind über `sima()->security()` erreichbar.

### Öffentliche WordPress-User-REST-Endpunkte deaktivieren

Standardmäßig entfernt SiMa Base folgende REST-Endpunkte:

```text
/wp/v2/users
/wp/v2/users/(?P<id>[\d]+)
```

Damit wird verhindert, dass Benutzer über die öffentliche REST API einfach ausgelesen werden können.

---

### User-Endpunkte wieder aktivieren

```php
sima()->security()->disablePublicUserEndpoint(false);
```

---

### User-Endpunkte deaktivieren

```php
sima()->security()->disablePublicUserEndpoint(true);
```

---

## Globale Helper-Funktionen

SiMa Base stellt mehrere globale Helper-Funktionen bereit.

---

### `sima()`

```php
sima();
```

Gibt die zentrale `SimaBase\SimaBase` Instanz zurück.

Beispiel:

```php
sima()->theme();
sima()->security();
sima()->plugin();
```

---

### `url($path)`

```php
url('/kontakt');
```

Erzeugt eine URL basierend auf dem aktuellen Page-Link.

---

### `formatBytes($size, $precision = 2)`

```php
formatBytes(2048);
```

Gibt Dateigrößen formatiert zurück.

Beispiel:

```text
2 KB
```

---

### `is_login_page()`

```php
if (is_login_page()) {
    // Login- oder Register-Seite
}
```

Prüft, ob die aktuelle Seite `wp-login.php` oder `wp-register.php` ist.

---

### `is_shop_page()`

```php
if (is_shop_page()) {
    // WooCommerce-Shop-Kontext
}
```

Prüft auf verschiedene WooCommerce-Seiten:

- Shop-Seite
- Produktkategorie
- Produktdetailseite
- Account-Seite
- WooCommerce Endpoint

Falls WooCommerce nicht aktiv ist, gibt die Funktion `false` zurück.

---

### `get_home_post_id()`

```php
get_home_post_id();
```

Gibt die ID der als Startseite gesetzten Seite zurück.

---

### `get_home_post()`

```php
get_home_post();
```

Gibt das Post-Objekt der Startseite zurück.

---

### `force_404()`

```php
force_404();
```

Erzwingt eine 404-Antwort und lädt das 404-Template.

---

### `asset($path)`

```php
asset('/assets/img/logo.svg');
```

Gibt eine URL relativ zum aktiven Theme-Verzeichnis zurück.

Beispiel:

```php
<img src="<?= asset('/assets/img/logo.svg'); ?>" alt="">
```

---

### `asset_contents($path)`

```php
asset_contents('/assets/icons/logo.svg');
```

Liest den Inhalt einer Datei aus dem aktiven Theme-Verzeichnis.

Praktisch z. B. für Inline-SVGs:

```php
<?= asset_contents('/assets/icons/logo.svg'); ?>
```

---

### `returnIf($if, $return = "", $else = null)`

```php
returnIf(is_front_page(), 'is-home');
```

Gibt abhängig von einer Bedingung einen Wert zurück.

---

### `echoIf($if, $return = "", $else = "")`

```php
echoIf(is_front_page(), 'is-home');
```

Gibt abhängig von einer Bedingung direkt einen Wert aus.

---

### `acf_img($image, $size = 'thumbnail', $attributes = [])`

```php
echo acf_img($image, 'large', [
    'class' => 'image-fluid',
]);
```

Erzeugt ein `<img>`-Tag aus einem ACF-Bildarray.

Erwartet ein ACF-Image-Array mit mindestens:

```php
$image['id'];
$image['alt'];
```

---

### `linked_acf_img($image, $size = 'thumbnail', $attributes = [])`

```php
echo linked_acf_img($image, 'large');
```

Erzeugt ein verlinktes ACF-Bild, das auf die Vollbild-Version zeigt.

Falls `slb_activate()` vorhanden ist, wird Simple Lightbox unterstützt.

---

### `momemt($dateTime = "now", $timezone = null, $immutableMode = false)`

```php
$date = momemt('2024-01-01');
```

Erzeugt eine neue `Moment\Moment` Instanz.

> Hinweis: Der Funktionsname lautet aktuell `momemt`. Falls das ein Tippfehler ist, sollte zusätzlich ein Alias `moment()` ergänzt werden.

---

## Plugin-Informationen

Über `sima()->plugin()` können Plugin-Daten und Hilfsfunktionen abgerufen werden.

---

### Plugin-Version abrufen

```php
sima()->plugin()->getVersion();
```

---

### Plugin-URL abrufen

```php
sima()->plugin()->getPluginUrl('/includes/css/style.css');
```

Gibt eine URL relativ zum Plugin-Verzeichnis zurück.

---

### Code im WordPress-Init ausführen

```php
sima()->plugin()->onLoad(function(\SimaBase\Plugin $plugin) {
    // Läuft auf init
});
```

---

### Code im Adminbereich ausführen

```php
sima()->plugin()->onAdminLoad(function(\SimaBase\Plugin $plugin) {
    // Läuft auf admin_init
});
```

---

## Cron Helper

Der Cron Helper stellt zusätzliche WP-Cron-Funktionen bereit.

```php
$cron = new \SimaBase\Helper\CronHelper();
```

---

### Eigenes Intervall hinzufügen

```php
$cron->addInterval('every_five_minutes', 'Every Five Minutes', 300);
```

---

### Minütliches Intervall

SiMa Base registriert automatisch folgendes Intervall:

```php
\SimaBase\Helper\CronHelper::PER_MINUTE
```

Dieses entspricht 60 Sekunden.

---

### Cron Event planen

```php
$cron->scheduleCron(
    \SimaBase\Helper\CronHelper::PER_MINUTE,
    'my_custom_cron_hook'
);
```

Danach kann der Hook normal verwendet werden:

```php
add_action('my_custom_cron_hook', function() {
    // Cron-Logik
});
```

---

## Plugin Updates

SiMa Base enthält einen einfachen Update-Checker.

Die Update-Daten werden standardmäßig aus folgender JSON-Datei geladen:

```text
https://raw.githubusercontent.com/simadesign/wp-sima-base/main/plugin.json
```

Die Daten werden für 2 Stunden in einem Site Transient gecached.

---

### Beispiel für `plugin.json`

```json
{
  "name": "SiMa Base",
  "slug": "sima-base",
  "version": "0.1.3",
  "tested": "6.5",
  "requires": "6.0",
  "requires_php": "8.1",
  "author": "SiMaDesign",
  "homepage": "https://simadesign.de",
  "download_url": "https://github.com/simadesign/wp-sima-base/archive/refs/tags/0.1.3.zip",
  "sections": {
    "description": "A WordPress base plugin for SiMa projects, providing utilities for custom theme development and essential security features.",
    "changelog": "Bug fixes and improvements."
  }
}
```

---

## Empfohlene Theme-Struktur

Beispielhafte Verwendung in der `functions.php` eines Custom Themes:

```php
<?php

defined('ABSPATH') || exit;

if (!function_exists('sima')) {
    return;
}

sima()->theme()
    ->useTitleBuilder()
    ->useCustomExcerptLength(32)
    ->menu('main', 'Primary menu')
    ->menu('footer', 'Footer menu');

sima()->theme()->onLoadFrontend(function(\SimaBase\Frontend\Theme $theme) {
    $theme
        ->styles()->useBootstrapGrid()
        ->styles()->useBootstrapUtils()
        ->styles()->useUtils()
        ->useThemeStyle('style', '/style.css', wp_get_theme()->get('Version'));

    $theme
        ->scripts()->useJquery();
});

sima()->theme()->getSocial()
    ->addInstagram()
    ->addFacebook()
    ->addLinkedIn();

sima()->theme()->getCustomizer()->build(function(\SimaBase\Admin\Customizer\Panel $panel) {
    $panel->addSection('footer', 'Footer')
        ->setPriority(2)
        ->addTextareaSetting('footer_text_left', 'Footer Text Left')
        ->addTextareaSetting('footer_text_right', 'Footer Text Right');
});
```

---

## Hinweise

- `sima()` sollte immer mit `function_exists('sima')` geprüft werden, wenn Code im Theme davon abhängig ist.
- `asset()` und `asset_contents()` beziehen sich auf das aktive Theme-Verzeichnis.
- Die Social-Media-Felder werden als Theme Mods gespeichert.
- Die öffentlichen WordPress-User-REST-Endpunkte sind standardmäßig deaktiviert.
- Für Updates wird eine externe `plugin.json` verwendet.
- Einige Helper setzen voraus, dass optionale Abhängigkeiten wie ACF, WooCommerce oder Bootstrap verfügbar sind.

---

## Lizenz

Dieses Plugin ist für interne SiMa-Projekte und Kundenprojekte vorgesehen.
