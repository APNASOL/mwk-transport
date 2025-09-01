<?php


// i18n bootstrap
if (session_status() === PHP_SESSION_NONE) session_start();

// 1) Supported languages and metadata
$SUPPORTED = [
    'en' => ['dir' => 'ltr', 'locale' => 'en_US'],
    'ur' => ['dir' => 'rtl', 'locale' => 'ur_PK']
];

// 2) Read desire from ?lang=, then session/cookie, else Accept-Language, else default
if (isset($_GET['lang']) && array_key_exists($_GET['lang'], $SUPPORTED)) {
    $_SESSION['lang'] = $_GET['lang'];
    setcookie('lang', $_GET['lang'], time() + 60*60*24*365, '/');
}

$lang = $_SESSION['lang'] ?? ($_COOKIE['lang'] ?? null);
if (!isset($SUPPORTED[$lang])) {
    $accept = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
    $lang = (stripos($accept, 'ur') !== false) ? 'ur' : 'en';
}
if (!isset($SUPPORTED[$lang])) $lang = 'en';

$GLOBALS['__DIR__'] = $SUPPORTED[$lang]['dir'];
$GLOBALS['__LOCALE__'] = $SUPPORTED[$lang]['locale'];
$GLOBALS['__LANG__'] = $lang;

// 3) Load messages
$messagesFile = __DIR__ . "/locales/{$lang}.php";
$messages = file_exists($messagesFile) ? require $messagesFile : [];

// 4) Translator
function __t(string $key, array $params = []): string {
    global $messages;
    $text = $messages[$key] ?? $key;
    foreach ($params as $k => $v) {
        $text = str_replace('{' . $k . '}', $v, $text);
    }
    return $text;
}

// 5) Helpers
function current_lang(): string { return $GLOBALS['__LANG__'] ?? 'en'; }
function current_dir(): string { return $GLOBALS['__DIR__'] ?? 'ltr'; }
function current_locale(): string { return $GLOBALS['__LOCALE__'] ?? 'en_US'; }

// 6) Intl formatters (dates & numbers)
if (class_exists('IntlDateFormatter')) {
    $GLOBALS['__DATEFMT__'] = new IntlDateFormatter(
        current_locale(),
        IntlDateFormatter::LONG,
        IntlDateFormatter::NONE,
        date_default_timezone_get()
    );
} else {
    $GLOBALS['__DATEFMT__'] = null;
}
function format_date($timestamp): string {
    if ($df = ($GLOBALS['__DATEFMT__'] ?? null)) {
        return $df->format($timestamp);
    }
    return date('F j, Y', $timestamp); // fallback
}

function format_currency(float $amount, string $currency = 'PKR'): string {
    if (class_exists('NumberFormatter')) {
        $nf = new NumberFormatter(current_locale(), NumberFormatter::CURRENCY);
        return $nf->formatCurrency($amount, $currency);
    }
    return $currency . ' ' . number_format($amount, 2); // fallback
}
