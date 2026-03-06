<?php

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

$artists = active_items(app_content()['artists']['cards']);

render_header('artists', 'Global Alliance Exhibition | Artists & Exhibitors');
?>
<section class="section">
    <div class="container">
        <p class="eyebrow">Artists / Exhibitors</p>
        <h1 class="page-title">Featured international exhibitors</h1>
        <p class="lead">
            The final lineup includes emerging and underrepresented artists selected for originality, technical quality, and global relevance.
        </p>

        <div class="artist-grid">
            <?php foreach ($artists as $item): ?>
                <article class="artist-card">
                    <img src="<?= public_image_url($item['image_path'] ?? null) ?>" alt="<?= h($item['image_alt'] ?? $item['title']) ?>">
                    <div>
                        <h2><?= h($item['title']) ?></h2>
                        <p class="muted line-icon"><?= svg_icon('location') ?><?= h($item['secondary_content']) ?></p>
                        <p><?= h($item['content']) ?></p>
                        <p class="line-icon"><?= svg_icon('list') ?><strong><?= h($item['extra_content']) ?></strong></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php render_footer(); ?>
