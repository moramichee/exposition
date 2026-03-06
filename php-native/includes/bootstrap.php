<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function app_content(): array
{
    static $content = null;

    if ($content === null) {
        $content = require __DIR__ . '/../data/content.php';
    }

    return $content;
}

function site_config(): array
{
    return app_content()['site'];
}

function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function asset_url(string $path): string
{
    return h($path);
}

function page_url(string $page): string
{
    $map = [
        'home' => 'index.php',
        'about' => 'about.php',
        'artists' => 'artists.php',
        'venue' => 'localisation.php',
        'press' => 'press.php',
        'contact' => 'contact.php',
        'privacy' => 'privacy-policy.php',
        'terms' => 'terms-and-conditions.php',
        'cookies' => 'cookie-notice.php',
        'copyright' => 'copyright.php',
    ];

    return $map[$page] ?? 'index.php';
}

function is_active_page(string $page, string $currentPage): bool
{
    return $page === $currentPage;
}

function svg_icon(string $name, string $class = 'icon'): string
{
    $icons = [
        'home' => '<path d="M3 11.5 12 4l9 7.5M6 9.5V20h12V9.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        'about' => '<path d="M12 4a8 8 0 1 0 8 8M12 10.5v6M12 7.5h.01" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        'artists' => '<path d="M5 19V8a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v11M9 6V4m6 2V4M4 19h16M8 12h8M8 15h5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        'location' => '<path d="M12 20s6-5.4 6-10a6 6 0 1 0-12 0c0 4.6 6 10 6 10ZM12 12a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        'press' => '<path d="M4 5h16v14H4zM8 9h8M8 13h8M8 17h5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        'contact' => '<path d="M4 6h16v12H4zM4 7l8 6 8-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        'calendar' => '<path d="M8 3v3M16 3v3M4 8h16M5 5h14a1 1 0 0 1 1 1v13H4V6a1 1 0 0 1 1-1Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        'user' => '<path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm-7 8a7 7 0 0 1 14 0" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        'document' => '<path d="M4 5h16v14H4zM8 9h8M8 13h8M8 17h5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        'shield' => '<path d="M7 12.5 10 16l7-8M12 3l7 3v6c0 4.2-2.5 7.8-7 9-4.5-1.2-7-4.8-7-9V6z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        'building' => '<path d="M2.5 17.5h19M6 17.5v-6l6-4 6 4v6M9 17.5v-3h6v3" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        'wave' => '<path d="m4 12 4-4 4 4 4-4 4 4M4 17h16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        'arrow-up' => '<path d="M12 20V8M12 8l-4 4M12 8l4 4M5 20h14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        'check' => '<path d="M4 12.5 9.5 18 20 7.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        'plane' => '<path d="M3 16h18M5 16l1.5-6h11L19 16M7 16a1.5 1.5 0 1 0 3 0m4.5 0a1.5 1.5 0 1 0 3 0M6.5 10l2.5-3h6l2.5 3" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        'hotel' => '<path d="M4 11.5h16M6 4h12a1 1 0 0 1 1 1v14H5V5a1 1 0 0 1 1-1Zm3 8v4m6-4v4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        'pulse' => '<path d="M3 12h4l2 5 4-10 2 5h6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        'list' => '<path d="M5 7.5h14M5 12h14M5 16.5h10" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>',
    ];

    $body = $icons[$name] ?? $icons['document'];

    return '<svg class="' . h($class) . '" viewBox="0 0 24 24" aria-hidden="true">' . $body . '</svg>';
}

function render_header(string $currentPage, string $title, ?string $description = null): void
{
    $site = site_config();
    $description = $description ?? 'Alliance Expo Monde / Global Alliance Exhibition in London, June 8-14, 2026.';

    $navItems = [
        'home' => ['label' => 'Home', 'icon' => 'home'],
        'about' => ['label' => 'About', 'icon' => 'about'],
        'artists' => ['label' => 'Artists', 'icon' => 'artists'],
        'venue' => ['label' => 'Localisation', 'icon' => 'location'],
        'press' => ['label' => 'Press', 'icon' => 'press'],
        'contact' => ['label' => 'Contact', 'icon' => 'contact'],
    ];

    echo '<!DOCTYPE html><html lang="en"><head>';
    echo '<meta charset="utf-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>' . h($title) . '</title>';
    echo '<meta name="description" content="' . h($description) . '">';
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    echo '<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">';
    echo '<link rel="stylesheet" href="' . asset_url('css/site.css') . '">';
    echo '</head><body>';
    echo '<header class="site-header"><div class="container nav-wrap">';
    echo '<a class="brand" href="' . h(page_url('home')) . '">';
    echo '<span class="brand-main">' . h($site['name']) . '</span>';
    echo '<span class="brand-sub">' . h($site['subtitle']) . '</span>';
    echo '</a>';
    echo '<nav class="site-nav" aria-label="Main">';

    foreach ($navItems as $key => $item) {
        $classes = is_active_page($key, $currentPage) ? 'active' : '';
        echo '<a href="' . h(page_url($key)) . '" class="' . h($classes) . '">';
        echo svg_icon($item['icon'], 'icon nav-icon');
        echo h($item['label']);
        echo '</a>';
    }

    echo '</nav></div></header><main>';
}

function render_footer(): void
{
    $site = site_config();

    echo '</main><footer class="site-footer"><div class="container footer-wrap"><div>';
    echo '<p class="footer-title">' . h($site['subtitle']) . '</p>';
    echo '<p>' . svg_icon('calendar', 'icon footer-icon') . h($site['dates'] . ' | ' . $site['hours'] . ' | ' . $site['venue']) . '</p>';
    echo '<p>' . svg_icon('contact', 'icon footer-icon') . 'Contact: <a href="mailto:' . h($site['email']) . '">' . h($site['email']) . '</a> | <a href="https://wa.me/' . h($site['whatsapp_digits']) . '" target="_blank" rel="noopener">' . h($site['whatsapp_raw']) . '</a></p>';
    echo '</div><nav class="legal-nav" aria-label="Legal">';
    echo '<a href="' . h(page_url('privacy')) . '">Privacy Policy</a>';
    echo '<a href="' . h(page_url('terms')) . '">Terms &amp; Conditions</a>';
    echo '<a href="' . h(page_url('cookies')) . '">Cookie Notice</a>';
    echo '<a href="' . h(page_url('copyright')) . '">Copyright</a>';
    echo '</nav></div></footer></body></html>';
}

function multiline_lines(?string $value): array
{
    if ($value === null || trim($value) === '') {
        return [];
    }

    return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $value) ?: [])));
}

function flash_set(string $key, string $message): void
{
    $_SESSION[$key] = $message;
}

function flash_pull(string $key): ?string
{
    $value = $_SESSION[$key] ?? null;
    unset($_SESSION[$key]);

    return is_string($value) ? $value : null;
}

function redirect_to(string $location): never
{
    header('Location: ' . $location);
    exit;
}

function normalize_form_input(string $field): string
{
    $value = $_POST[$field] ?? '';

    return is_string($value) ? trim($value) : '';
}

function process_registration_form(): array
{
    $content = app_content();
    $status = flash_pull('status');
    $errors = [];
    $old = [
        'full_name' => '',
        'country_city' => '',
        'artwork_types' => '',
        'gender' => '',
        'email' => '',
        'whatsapp' => '',
    ];

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        return [$errors, $old, $status];
    }

    foreach (array_keys($old) as $field) {
        $old[$field] = normalize_form_input($field);
    }

    foreach (['full_name', 'country_city', 'artwork_types', 'gender', 'email', 'whatsapp'] as $field) {
        if ($old[$field] === '') {
            $errors[$field] = 'This field is required.';
        }
    }

    $allowedGenders = ['Female', 'Male', 'Non-binary', 'Prefer not to say'];
    if ($old['gender'] !== '' && ! in_array($old['gender'], $allowedGenders, true)) {
        $errors['gender'] = 'Please select a valid option.';
    }

    if ($old['email'] !== '' && filter_var($old['email'], FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if ($errors !== []) {
        return [$errors, $old, $status];
    }

    $payload = [
        'submitted_at' => gmdate('c'),
        'full_name' => $old['full_name'],
        'country_city' => $old['country_city'],
        'artwork_types' => $old['artwork_types'],
        'gender' => $old['gender'],
        'email' => $old['email'],
        'whatsapp' => $old['whatsapp'],
    ];

    $storageDir = __DIR__ . '/../storage';
    if (! is_dir($storageDir)) {
        mkdir($storageDir, 0775, true);
    }

    file_put_contents(
        $storageDir . '/registrations.jsonl',
        json_encode($payload, JSON_UNESCAPED_SLASHES) . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );

    flash_set('status', $content['contact']['success_message']);
    redirect_to(page_url('contact'));
}

function output_press_release_pdf(): never
{
    $lines = [
        'Global Alliance Exhibition - Official Press Release',
        'Venue: ExCel London Hotel, London',
        'Dates: June 8-14, 2026 | 10:00-16:00',
        'Organizer: Francois Pinault',
        'Partners: easyJet and Ryanair',
        'Contact: pinault12art@gmail.com | WhatsApp +447405854019',
    ];

    $stream = "BT\n/F1 18 Tf\n72 760 Td\n";
    $stream .= '(' . pdf_escape($lines[0]) . ") Tj\n";
    $stream .= "/F1 12 Tf\n";

    foreach (array_slice($lines, 1) as $line) {
        $stream .= "0 -24 Td\n(" . pdf_escape($line) . ") Tj\n";
    }

    $stream .= 'ET';

    $objects = [
        '<< /Type /Catalog /Pages 2 0 R >>',
        '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
        '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 5 0 R >> >> /Contents 4 0 R >>',
        "<< /Length " . strlen($stream) . " >>\nstream\n{$stream}\nendstream",
        '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
    ];

    $pdf = "%PDF-1.4\n";
    $offsets = [];

    foreach ($objects as $index => $object) {
        $offsets[] = strlen($pdf);
        $objectNumber = $index + 1;
        $pdf .= "{$objectNumber} 0 obj\n{$object}\nendobj\n";
    }

    $xrefOffset = strlen($pdf);
    $pdf .= "xref\n0 " . (count($objects) + 1) . "\n";
    $pdf .= "0000000000 65535 f \n";

    foreach ($offsets as $offset) {
        $pdf .= sprintf("%010d 00000 n \n", $offset);
    }

    $pdf .= "trailer\n";
    $pdf .= '<< /Size ' . (count($objects) + 1) . " /Root 1 0 R >>\n";
    $pdf .= "startxref\n{$xrefOffset}\n%%EOF";

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="global-alliance-exhibition-press-release.pdf"');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    echo $pdf;
    exit;
}

function pdf_escape(string $text): string
{
    return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
}

function render_legal_page(string $pageKey): void
{
    $content = app_content()['legal'][$pageKey];
    render_header($pageKey, $content['title'] . ' | Global Alliance Exhibition');

    echo '<section class="section"><div class="container legal-content">';
    echo '<p class="eyebrow">Legal</p>';
    echo '<h1 class="page-title">' . h($content['title']) . '</h1>';

    foreach ($content['paragraphs'] as $paragraph) {
        echo '<p>' . h($paragraph) . '</p>';
    }

    echo '</div></section>';
    render_footer();
}
