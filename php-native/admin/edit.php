<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin.php';

admin_require_auth();

$content = admin_load_content();
$pageLabels = admin_page_labels();
$pageSectionMap = admin_page_section_map();

$sourcePage = trim((string) ($_REQUEST['page'] ?? $_REQUEST['source_page'] ?? ''));
$sourceSection = trim((string) ($_REQUEST['section'] ?? $_REQUEST['source_section'] ?? ''));
$sourceIndex = filter_var($_REQUEST['index'] ?? $_REQUEST['source_index'] ?? null, FILTER_VALIDATE_INT);

if ($sourcePage === '' || $sourceSection === '' || $sourceIndex === false) {
    flash_set('admin_status', 'Element introuvable.');
    redirect_to('index.php');
}

$existing = admin_get_element($content, $sourcePage, $sourceSection, (int) $sourceIndex);
if ($existing === null) {
    flash_set('admin_status', 'Element introuvable.');
    redirect_to('index.php');
}

$values = $existing + ['remove_image' => false];
$errors = [];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    [$validated, $errors] = admin_validate_element_form($_POST);
    $values = array_merge($values, $validated);
    $values['remove_image'] = isset($_POST['remove_image']) && (string) $_POST['remove_image'] === '1';
    $newImagePath = $existing['image_path'];

    if ($values['remove_image']) {
        admin_delete_local_image($newImagePath);
        $newImagePath = '';
    } elseif ($values['image_path'] !== $existing['image_path'] && admin_is_local_image($existing['image_path'])) {
        admin_delete_local_image($existing['image_path']);
        $newImagePath = $values['image_path'];
    } else {
        $newImagePath = $values['image_path'];
    }

    $uploadError = $_FILES['image_upload']['error'] ?? UPLOAD_ERR_NO_FILE;
    if ($uploadError !== UPLOAD_ERR_NO_FILE) {
        $uploadedPath = admin_store_uploaded_image($_FILES['image_upload']);
        if ($uploadedPath === null) {
            $errors['image_upload'] = 'Upload image invalide.';
        } else {
            admin_delete_local_image($existing['image_path']);
            $newImagePath = $uploadedPath;
        }
    }

    $values['image_path'] = $newImagePath;
    if ($values['image_alt'] === '' && $values['image_path'] !== '') {
        $values['image_alt'] = $values['title'];
    }

    if ($errors === []) {
        $sourceItems =& admin_section_reference($content, $sourcePage, $sourceSection);
        admin_remove_element($sourceItems, (int) $sourceIndex);

        $targetItems =& admin_section_reference($content, $values['page'], $values['section']);
        admin_insert_element($targetItems, [
            'title' => $values['title'],
            'content' => $values['content'],
            'secondary_content' => $values['secondary_content'],
            'extra_content' => $values['extra_content'],
            'image_path' => $values['image_path'],
            'image_alt' => $values['image_alt'],
            'is_active' => $values['is_active'],
        ], (int) $values['sort_order']);

        admin_save_content($content);
        flash_set('admin_status', 'Element modifie avec succes.');
        redirect_to('index.php');
    }
}

render_admin_header('Admin | Modifier un element', 'elements');
?>
<h1 class="page-title">Modifier un element</h1>
<p class="muted admin-context">Contexte: Page <?= h($pageLabels[$sourcePage]) ?> / <?= h($pageSectionMap[$sourcePage][$sourceSection]) ?></p>
<?php
$formAction = 'edit.php';
$submitLabel = 'Mettre a jour';
$isEdit = true;
require __DIR__ . '/_form.php';
render_admin_footer();
?>
