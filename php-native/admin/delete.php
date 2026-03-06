<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin.php';

admin_require_auth();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    redirect_to('index.php');
}

$page = trim((string) ($_POST['page'] ?? ''));
$section = trim((string) ($_POST['section'] ?? ''));
$index = filter_var($_POST['index'] ?? null, FILTER_VALIDATE_INT);

if ($page === '' || $section === '' || $index === false) {
    flash_set('admin_status', 'Suppression impossible.');
    redirect_to('index.php');
}

$content = admin_load_content();
$items =& admin_section_reference($content, $page, $section);
$removed = admin_remove_element($items, (int) $index);

if ($removed === null) {
    flash_set('admin_status', 'Element introuvable.');
    redirect_to('index.php');
}

admin_delete_local_image((string) ($removed['image_path'] ?? ''));
admin_save_content($content);
flash_set('admin_status', 'Element supprime avec succes.');
redirect_to('index.php');
