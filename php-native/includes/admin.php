<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

function admin_credentials(): array
{
    return [
        'email' => 'admin@communiquer.local',
        'password_hash' => '$2y$10$7gLNdkZTWocw.lYGbi2BWOiRLseODQDEXV2rbL16CNGaivhtuTXka',
    ];
}

function admin_page_labels(): array
{
    return [
        'home' => 'Home',
        'about' => 'About',
        'artists' => 'Artists',
        'venue' => 'Venue',
        'press' => 'Press',
        'contact' => 'Contact',
    ];
}

function admin_page_section_map(): array
{
    return [
        'home' => [
            'hero_meta' => 'Cartes Hero (date, lieu, organisateur...)',
            'highlight' => 'Highlights (cartes du bas)',
        ],
        'about' => [
            'mission_cards' => 'Blocs Concept / Theme / Vision / Objectifs',
            'biography' => 'Biographie organisateur',
        ],
        'artists' => [
            'artist_cards' => 'Cartes artistes',
        ],
        'venue' => [
            'info_cards' => 'Informations lieu/date',
            'support_cards' => 'Transport et hebergement',
        ],
        'press' => [
            'partner_cards' => 'Partenaires',
            'resource_cards' => 'Ressources presse',
        ],
        'contact' => [
            'channel_cards' => 'Canaux officiels',
            'terms_cards' => 'Conditions de participation',
        ],
    ];
}

function admin_is_authenticated(): bool
{
    return ($_SESSION['native_admin_authenticated'] ?? false) === true;
}

function admin_require_auth(): void
{
    if (! admin_is_authenticated()) {
        flash_set('admin_error', 'Connectez-vous pour acceder a l administration.');
        redirect_to('login.php');
    }
}

function admin_attempt_login(string $email, string $password): bool
{
    $credentials = admin_credentials();

    if ($email !== $credentials['email']) {
        return false;
    }

    if (! password_verify($password, $credentials['password_hash'])) {
        return false;
    }

    $_SESSION['native_admin_authenticated'] = true;

    return true;
}

function admin_logout(): void
{
    unset($_SESSION['native_admin_authenticated']);
    session_regenerate_id(true);
}

function render_admin_header(string $title, string $active = 'elements'): void
{
    echo '<!DOCTYPE html><html lang="fr"><head>';
    echo '<meta charset="utf-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>' . h($title) . '</title>';
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    echo '<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">';
    echo '<link rel="stylesheet" href="../css/site.css">';
    echo '</head><body>';
    echo '<header class="site-header admin-header"><div class="container nav-wrap">';
    echo '<a class="brand" href="index.php"><span class="brand-main">Administration</span><span class="brand-sub">Global Alliance Exhibition</span></a>';
    echo '<nav class="site-nav" aria-label="Admin navigation">';
    echo '<a href="index.php" class="' . ($active === 'elements' ? 'active' : '') . '">Elements</a>';
    echo '<a href="create.php" class="' . ($active === 'create' ? 'active' : '') . '">Ajouter</a>';
    echo '<a href="../index.php" target="_blank" rel="noopener">Voir le site</a>';
    echo '<form method="post" action="logout.php"><button type="submit" class="btn btn-secondary btn-sm">Se deconnecter</button></form>';
    echo '</nav></div></header><main class="section admin-main"><div class="container">';

    $status = flash_pull('admin_status');
    if ($status !== null) {
        echo '<div class="alert-success">' . h($status) . '</div>';
    }
}

function render_admin_footer(): void
{
    echo '</div></main></body></html>';
}

function admin_storage_upload_dir(): string
{
    return dirname(__DIR__) . '/storage/uploads';
}

function admin_is_local_image(?string $path): bool
{
    if ($path === null || $path === '') {
        return false;
    }

    return ! str_starts_with($path, 'http://') && ! str_starts_with($path, 'https://');
}

function admin_image_url(?string $path): string
{
    if ($path === null || $path === '') {
        return '';
    }

    if (admin_is_local_image($path)) {
        return '../' . ltrim($path, '/');
    }

    return $path;
}

function admin_delete_local_image(?string $path): void
{
    if (! admin_is_local_image($path)) {
        return;
    }

    $absolutePath = dirname(__DIR__) . '/' . ltrim($path, '/');
    if (is_file($absolutePath)) {
        unlink($absolutePath);
    }
}

function admin_store_uploaded_image(array $file): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return null;
    }

    $tmpName = $file['tmp_name'] ?? '';
    if (! is_string($tmpName) || $tmpName === '') {
        return null;
    }

    $mime = mime_content_type($tmpName);
    if (! is_string($mime) || ! str_starts_with($mime, 'image/')) {
        return null;
    }

    $uploadDir = admin_storage_upload_dir();
    if (! is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    $originalName = (string) ($file['name'] ?? 'image');
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    if ($extension === '') {
        $extension = 'jpg';
    }

    $fileName = 'image-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $extension;
    $destination = $uploadDir . '/' . $fileName;

    if (! move_uploaded_file($tmpName, $destination)) {
        return null;
    }

    return 'storage/uploads/' . $fileName;
}

function admin_load_content(): array
{
    return app_content();
}

function admin_save_content(array $content): void
{
    $storagePath = content_storage_path();
    $storageDir = dirname($storagePath);

    if (! is_dir($storageDir)) {
        mkdir($storageDir, 0775, true);
    }

    file_put_contents(
        $storagePath,
        json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        LOCK_EX
    );
}

function admin_section_items(array $content, string $page, string $section): array
{
    return match ($page . ':' . $section) {
        'home:hero_meta' => $content['home']['meta_items'] ?? [],
        'home:highlight' => $content['home']['highlights'] ?? [],
        'about:mission_cards' => $content['about']['mission_cards'] ?? [],
        'about:biography' => $content['about']['biography_cards'] ?? [],
        'artists:artist_cards' => $content['artists']['cards'] ?? [],
        'venue:info_cards' => $content['venue']['info_cards'] ?? [],
        'venue:support_cards' => $content['venue']['support_cards'] ?? [],
        'press:partner_cards' => $content['press']['partner_cards'] ?? [],
        'press:resource_cards' => $content['press']['resource_cards'] ?? [],
        'contact:channel_cards' => $content['contact']['channel_cards'] ?? [],
        'contact:terms_cards' => $content['contact']['terms_cards'] ?? [],
        default => [],
    };
}

function &admin_section_reference(array &$content, string $page, string $section): array
{
    if (! isset($content[$page]) || ! is_array($content[$page])) {
        $content[$page] = [];
    }

    switch ($page . ':' . $section) {
        case 'home:hero_meta':
            if (! isset($content['home']['meta_items']) || ! is_array($content['home']['meta_items'])) {
                $content['home']['meta_items'] = [];
            }
            return $content['home']['meta_items'];
        case 'home:highlight':
            if (! isset($content['home']['highlights']) || ! is_array($content['home']['highlights'])) {
                $content['home']['highlights'] = [];
            }
            return $content['home']['highlights'];
        case 'about:mission_cards':
            if (! isset($content['about']['mission_cards']) || ! is_array($content['about']['mission_cards'])) {
                $content['about']['mission_cards'] = [];
            }
            return $content['about']['mission_cards'];
        case 'about:biography':
            if (! isset($content['about']['biography_cards']) || ! is_array($content['about']['biography_cards'])) {
                $content['about']['biography_cards'] = [];
            }
            return $content['about']['biography_cards'];
        case 'artists:artist_cards':
            if (! isset($content['artists']['cards']) || ! is_array($content['artists']['cards'])) {
                $content['artists']['cards'] = [];
            }
            return $content['artists']['cards'];
        case 'venue:info_cards':
            if (! isset($content['venue']['info_cards']) || ! is_array($content['venue']['info_cards'])) {
                $content['venue']['info_cards'] = [];
            }
            return $content['venue']['info_cards'];
        case 'venue:support_cards':
            if (! isset($content['venue']['support_cards']) || ! is_array($content['venue']['support_cards'])) {
                $content['venue']['support_cards'] = [];
            }
            return $content['venue']['support_cards'];
        case 'press:partner_cards':
            if (! isset($content['press']['partner_cards']) || ! is_array($content['press']['partner_cards'])) {
                $content['press']['partner_cards'] = [];
            }
            return $content['press']['partner_cards'];
        case 'press:resource_cards':
            if (! isset($content['press']['resource_cards']) || ! is_array($content['press']['resource_cards'])) {
                $content['press']['resource_cards'] = [];
            }
            return $content['press']['resource_cards'];
        case 'contact:channel_cards':
            if (! isset($content['contact']['channel_cards']) || ! is_array($content['contact']['channel_cards'])) {
                $content['contact']['channel_cards'] = [];
            }
            return $content['contact']['channel_cards'];
        case 'contact:terms_cards':
            if (! isset($content['contact']['terms_cards']) || ! is_array($content['contact']['terms_cards'])) {
                $content['contact']['terms_cards'] = [];
            }
            return $content['contact']['terms_cards'];
        default:
            static $empty = [];
            return $empty;
    }
}

function admin_flatten_elements(array $content): array
{
    $elements = [];

    foreach (admin_page_section_map() as $page => $sections) {
        foreach ($sections as $section => $label) {
            foreach (admin_section_items($content, $page, $section) as $index => $item) {
                $elements[] = [
                    'page' => $page,
                    'section' => $section,
                    'section_label' => $label,
                    'source_index' => $index,
                    'sort_order' => $index + 1,
                    'title' => (string) ($item['title'] ?? ''),
                    'content' => (string) ($item['content'] ?? ''),
                    'secondary_content' => (string) ($item['secondary_content'] ?? ''),
                    'extra_content' => (string) ($item['extra_content'] ?? ''),
                    'image_path' => (string) ($item['image_path'] ?? ''),
                    'image_alt' => (string) ($item['image_alt'] ?? ''),
                    'is_active' => ! array_key_exists('is_active', $item) || (bool) $item['is_active'],
                ];
            }
        }
    }

    return $elements;
}

function admin_get_element(array $content, string $page, string $section, int $sourceIndex): ?array
{
    $items = admin_section_items($content, $page, $section);

    if (! array_key_exists($sourceIndex, $items)) {
        return null;
    }

    $item = $items[$sourceIndex];

    return [
        'page' => $page,
        'section' => $section,
        'source_index' => $sourceIndex,
        'sort_order' => $sourceIndex + 1,
        'title' => (string) ($item['title'] ?? ''),
        'content' => (string) ($item['content'] ?? ''),
        'secondary_content' => (string) ($item['secondary_content'] ?? ''),
        'extra_content' => (string) ($item['extra_content'] ?? ''),
        'image_path' => (string) ($item['image_path'] ?? ''),
        'image_alt' => (string) ($item['image_alt'] ?? ''),
        'is_active' => ! array_key_exists('is_active', $item) || (bool) $item['is_active'],
    ];
}

function admin_next_sort_order(array $content, string $page, string $section): int
{
    return count(admin_section_items($content, $page, $section)) + 1;
}

function admin_validate_element_form(array $post): array
{
    $page = trim((string) ($post['page'] ?? ''));
    $section = trim((string) ($post['section'] ?? ''));
    $title = trim((string) ($post['title'] ?? ''));
    $content = trim((string) ($post['content'] ?? ''));
    $secondaryContent = trim((string) ($post['secondary_content'] ?? ''));
    $extraContent = trim((string) ($post['extra_content'] ?? ''));
    $imagePath = trim((string) ($post['image_path'] ?? ''));
    $imageAlt = trim((string) ($post['image_alt'] ?? ''));
    $sortOrderRaw = trim((string) ($post['sort_order'] ?? '1'));
    $isActive = isset($post['is_active']) && (string) $post['is_active'] === '1';

    $errors = [];
    $pageSections = admin_page_section_map();

    if (! isset($pageSections[$page])) {
        $errors['page'] = 'Page invalide.';
    }

    if (! isset($pageSections[$page][$section])) {
        $errors['section'] = 'Section invalide.';
    }

    if ($title === '') {
        $errors['title'] = 'Le titre est requis.';
    }

    $sortOrder = filter_var($sortOrderRaw, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($sortOrder === false) {
        $errors['sort_order'] = 'Ordre invalide.';
        $sortOrder = 1;
    }

    return [[
        'page' => $page,
        'section' => $section,
        'title' => $title,
        'content' => $content,
        'secondary_content' => $secondaryContent,
        'extra_content' => $extraContent,
        'image_path' => $imagePath,
        'image_alt' => $imageAlt,
        'sort_order' => (int) $sortOrder,
        'is_active' => $isActive,
    ], $errors];
}

function admin_insert_element(array &$items, array $element, int $sortOrder): void
{
    $position = max(0, min($sortOrder - 1, count($items)));
    array_splice($items, $position, 0, [$element]);
    $items = array_values($items);
}

function admin_remove_element(array &$items, int $sourceIndex): ?array
{
    if (! array_key_exists($sourceIndex, $items)) {
        return null;
    }

    $removed = $items[$sourceIndex];
    array_splice($items, $sourceIndex, 1);
    $items = array_values($items);

    return $removed;
}
