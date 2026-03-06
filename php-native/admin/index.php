<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin.php';

admin_require_auth();

$content = admin_load_content();
$elements = admin_flatten_elements($content);
$pageLabels = admin_page_labels();
$pageSectionMap = admin_page_section_map();

render_admin_header('Admin | Elements', 'elements');
?>
<div class="admin-heading">
    <h1 class="page-title">Elements dynamiques</h1>
    <a href="create.php" class="btn btn-primary">Nouvel element</a>
</div>

<?php foreach ($pageLabels as $pageKey => $pageLabel): ?>
    <section class="admin-block">
        <h2>Page: <?= h($pageLabel) ?></h2>

        <?php foreach ($pageSectionMap[$pageKey] as $sectionKey => $sectionLabel): ?>
            <div class="admin-section-header">
                <h3 class="admin-section-title"><?= h($sectionLabel) ?></h3>
                <a href="create.php?page=<?= h($pageKey) ?>&section=<?= h($sectionKey) ?>" class="btn btn-primary btn-sm">Ajouter un element</a>
            </div>

            <?php
            $sectionElements = array_values(array_filter(
                $elements,
                static fn (array $element): bool => $element['page'] === $pageKey && $element['section'] === $sectionKey
            ));
            ?>

            <?php if ($sectionElements === []): ?>
                <p class="muted">Aucun element dans cette section.</p>
            <?php else: ?>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Ordre</th>
                                <th>Titre</th>
                                <th>Contenu</th>
                                <th>Image</th>
                                <th>Actif</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sectionElements as $element): ?>
                                <tr>
                                    <td><?= h((string) $element['sort_order']) ?></td>
                                    <td><?= h($element['title']) ?></td>
                                    <td>
                                        <div><?= h($element['content']) ?></div>
                                        <?php if ($element['secondary_content'] !== ''): ?><div class="muted"><?= h($element['secondary_content']) ?></div><?php endif; ?>
                                        <?php if ($element['extra_content'] !== ''): ?><div class="muted"><?= h($element['extra_content']) ?></div><?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($element['image_path'] !== ''): ?>
                                            <img src="<?= h(admin_image_url($element['image_path'])) ?>" alt="<?= h($element['image_alt'] !== '' ? $element['image_alt'] : $element['title']) ?>" class="admin-thumb">
                                        <?php else: ?>
                                            <span class="muted">Aucune</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $element['is_active'] ? 'Oui' : 'Non' ?></td>
                                    <td class="admin-actions">
                                        <a href="edit.php?page=<?= h($element['page']) ?>&section=<?= h($element['section']) ?>&index=<?= h((string) $element['source_index']) ?>" class="btn btn-primary btn-sm">Modifier</a>
                                        <form method="post" action="delete.php" onsubmit="return confirm('Supprimer cet element ?');">
                                            <input type="hidden" name="page" value="<?= h($element['page']) ?>">
                                            <input type="hidden" name="section" value="<?= h($element['section']) ?>">
                                            <input type="hidden" name="index" value="<?= h((string) $element['source_index']) ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </section>
<?php endforeach; ?>
<?php render_admin_footer(); ?>
