<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin.php';

admin_require_auth();

$content = admin_load_content();
$pageLabels = admin_page_labels();
$pageSectionMap = admin_page_section_map();

$queryPage = trim((string) ($_GET['page'] ?? 'home'));
$querySection = trim((string) ($_GET['section'] ?? 'hero_meta'));

if (! isset($pageSectionMap[$queryPage])) {
    $queryPage = 'home';
}
if (! isset($pageSectionMap[$queryPage][$querySection])) {
    $querySection = array_key_first($pageSectionMap[$queryPage]);
}

$values = [
    'page' => $queryPage,
    'section' => (string) $querySection,
    'title' => '',
    'content' => '',
    'secondary_content' => '',
    'extra_content' => '',
    'image_path' => '',
    'image_alt' => '',
    'sort_order' => admin_next_sort_order($content, $queryPage, (string) $querySection),
    'is_active' => true,
    'remove_image' => false,
];
$errors = [];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    [$validated, $errors] = admin_validate_element_form($_POST);
    $values = array_merge($values, $validated);
    $uploadError = $_FILES['image_upload']['error'] ?? UPLOAD_ERR_NO_FILE;

    if ($uploadError !== UPLOAD_ERR_NO_FILE) {
        $uploadedPath = admin_store_uploaded_image($_FILES['image_upload']);
        if ($uploadedPath === null) {
            $errors['image_upload'] = 'Upload image invalide.';
        } else {
            $values['image_path'] = $uploadedPath;
        }
    }

    if ($values['image_alt'] === '' && $values['image_path'] !== '') {
        $values['image_alt'] = $values['title'];
    }

    if ($errors === []) {
        $items =& admin_section_reference($content, $values['page'], $values['section']);
        admin_insert_element($items, [
            'title' => $values['title'],
            'content' => $values['content'],
            'secondary_content' => $values['secondary_content'],
            'extra_content' => $values['extra_content'],
            'image_path' => $values['image_path'],
            'image_alt' => $values['image_alt'],
            'is_active' => $values['is_active'],
        ], (int) $values['sort_order']);

        admin_save_content($content);
        flash_set('admin_status', 'Element ajoute avec succes.');
        redirect_to('index.php');
    }
}

render_admin_header('Admin | Ajouter un element', 'create');
?>
<h1 class="page-title">Ajouter un element</h1>
<p class="muted admin-context">Contexte: Page <?= h($pageLabels[$values['page']]) ?> / <?= h($pageSectionMap[$values['page']][$values['section']]) ?></p>
<?php
$formAction = 'create.php';
$submitLabel = 'Ajouter';
$isEdit = false;
$sourcePage = '';
$sourceSection = '';
$sourceIndex = 0;
require __DIR__ . '/_form.php';
render_admin_footer();
?>
