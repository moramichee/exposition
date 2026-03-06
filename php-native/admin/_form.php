<article class="card admin-card">
    <form method="post" action="<?= h($formAction) ?>" class="admin-form" enctype="multipart/form-data">
        <?php if ($isEdit): ?>
            <input type="hidden" name="source_page" value="<?= h($sourcePage) ?>">
            <input type="hidden" name="source_section" value="<?= h($sourceSection) ?>">
            <input type="hidden" name="source_index" value="<?= h((string) $sourceIndex) ?>">
        <?php endif; ?>

        <div class="admin-form-grid">
            <label for="page">
                Page
                <select id="page" name="page" required>
                    <?php foreach ($pageLabels as $value => $label): ?>
                        <option value="<?= h($value) ?>" <?= $values['page'] === $value ? 'selected' : '' ?>><?= h($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <?php if (isset($errors['page'])): ?><p class="error"><?= h($errors['page']) ?></p><?php endif; ?>

            <label for="section">
                Section
                <select id="section" name="section" required>
                    <?php foreach ($pageSectionMap as $pageKey => $sections): ?>
                        <optgroup label="<?= h($pageLabels[$pageKey]) ?>">
                            <?php foreach ($sections as $value => $label): ?>
                                <option value="<?= h($value) ?>" data-page="<?= h($pageKey) ?>" <?= $values['section'] === $value ? 'selected' : '' ?>><?= h($label) ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </label>
            <?php if (isset($errors['section'])): ?><p class="error"><?= h($errors['section']) ?></p><?php endif; ?>

            <label for="title">
                Titre
                <input id="title" type="text" name="title" value="<?= h($values['title']) ?>" maxlength="150" required>
            </label>
            <?php if (isset($errors['title'])): ?><p class="error"><?= h($errors['title']) ?></p><?php endif; ?>

            <label for="content">
                Contenu principal
                <textarea id="content" name="content" rows="3" maxlength="500"><?= h($values['content']) ?></textarea>
            </label>
            <?php if (isset($errors['content'])): ?><p class="error"><?= h($errors['content']) ?></p><?php endif; ?>

            <label for="secondary_content">
                Contenu secondaire
                <textarea id="secondary_content" name="secondary_content" rows="3" maxlength="500"><?= h($values['secondary_content']) ?></textarea>
            </label>
            <?php if (isset($errors['secondary_content'])): ?><p class="error"><?= h($errors['secondary_content']) ?></p><?php endif; ?>

            <label for="extra_content">
                Contenu complementaire
                <textarea id="extra_content" name="extra_content" rows="4" maxlength="2000"><?= h($values['extra_content']) ?></textarea>
            </label>
            <?php if (isset($errors['extra_content'])): ?><p class="error"><?= h($errors['extra_content']) ?></p><?php endif; ?>

            <label for="image_path">
                URL image
                <input id="image_path" type="text" name="image_path" value="<?= h($values['image_path']) ?>" maxlength="1000">
            </label>
            <?php if (isset($errors['image_path'])): ?><p class="error"><?= h($errors['image_path']) ?></p><?php endif; ?>

            <label for="image_upload">
                Image (upload)
                <input id="image_upload" type="file" name="image_upload" accept="image/*">
            </label>
            <?php if (isset($errors['image_upload'])): ?><p class="error"><?= h($errors['image_upload']) ?></p><?php endif; ?>

            <label for="image_alt">
                Texte alternatif image
                <input id="image_alt" type="text" name="image_alt" value="<?= h($values['image_alt']) ?>" maxlength="180">
            </label>
            <?php if (isset($errors['image_alt'])): ?><p class="error"><?= h($errors['image_alt']) ?></p><?php endif; ?>

            <?php if ($values['image_path'] !== ''): ?>
                <div class="admin-image-current">
                    <p class="muted">Image actuelle</p>
                    <img src="<?= h(admin_image_url($values['image_path'])) ?>" alt="<?= h($values['image_alt'] !== '' ? $values['image_alt'] : $values['title']) ?>" class="admin-thumb admin-thumb-large">
                    <label class="admin-check" for="remove_image">
                        <input id="remove_image" type="checkbox" name="remove_image" value="1" <?= !empty($values['remove_image']) ? 'checked' : '' ?>>
                        Supprimer l'image
                    </label>
                </div>
            <?php endif; ?>

            <label for="sort_order">
                Ordre d'affichage
                <input id="sort_order" type="number" name="sort_order" min="1" max="999" value="<?= h((string) $values['sort_order']) ?>" required>
            </label>
            <?php if (isset($errors['sort_order'])): ?><p class="error"><?= h($errors['sort_order']) ?></p><?php endif; ?>

            <label class="admin-check" for="is_active">
                <input id="is_active" type="checkbox" name="is_active" value="1" <?= !empty($values['is_active']) ? 'checked' : '' ?>>
                Element actif
            </label>
        </div>

        <div class="admin-form-actions">
            <button type="submit" class="btn btn-primary"><?= h($submitLabel) ?></button>
            <a href="index.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</article>

<script>
    (function () {
        const pageSelect = document.getElementById('page');
        const sectionSelect = document.getElementById('section');
        if (!pageSelect || !sectionSelect) return;

        const allOptions = Array.from(sectionSelect.querySelectorAll('option[data-page]'));

        const filterSections = () => {
            const selectedPage = pageSelect.value;
            let hasSelected = false;

            allOptions.forEach((option) => {
                const visible = option.dataset.page === selectedPage;
                option.hidden = !visible;
                if (!visible && option.selected) {
                    option.selected = false;
                }
                if (visible && option.selected) {
                    hasSelected = true;
                }
            });

            if (!hasSelected) {
                const firstVisible = allOptions.find((option) => option.dataset.page === selectedPage);
                if (firstVisible) {
                    firstVisible.selected = true;
                }
            }
        };

        pageSelect.addEventListener('change', filterSections);
        filterSections();
    })();
</script>
