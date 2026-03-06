<?php

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

$content = app_content();
$home = $content['home'];
$metaItems = active_items($home['meta_items']);
$highlights = active_items($home['highlights']);

render_header(
    'home',
    'Global Alliance Exhibition | Home',
    'International art exhibition in London. Discover dates, venue and registration details for Alliance Expo Monde / Global Alliance Exhibition.'
);
?>
<section class="hero">
    <div class="hero-backdrop" aria-hidden="true"></div>
    <div class="container hero-content">
        <p class="eyebrow"><?= h(site_config()['name']) ?> / <?= h(site_config()['subtitle']) ?></p>
        <h1><?= h($home['hero_title']) ?></h1>
        <p class="lead"><?= h($home['hero_lead']) ?></p>

        <div class="meta-grid">
            <?php foreach ($metaItems as $index => $item): ?>
                <article class="meta-item">
                    <div class="meta-head">
                        <span class="icon-chip" aria-hidden="true">
                            <?php
                            $icon = $index % 3 === 0 ? 'calendar' : ($index % 3 === 1 ? 'location' : 'user');
                            echo svg_icon($icon);
                            ?>
                        </span>
                        <h2><?= h($item['title']) ?></h2>
                    </div>
                    <?php if (!empty($item['content'])): ?><p><?= h($item['content']) ?></p><?php endif; ?>
                    <?php if (!empty($item['secondary_content'])): ?><p><?= h($item['secondary_content']) ?></p><?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="actions">
            <a class="btn btn-primary" href="<?= h(page_url('contact')) ?>">Register / Attend</a>
            <a class="btn btn-secondary" href="<?= h(page_url('about')) ?>">Learn More</a>
            <button type="button" class="btn btn-secondary" id="toggle-invited-artists" aria-expanded="false" aria-controls="invited-artists-panel">
                See more
            </button>
        </div>

        <div class="hero-extra" id="invited-artists-panel" hidden>
            <h2>Invited artists:</h2>
            <ul class="hero-list">
                <?php foreach ($home['invited_artists'] as $artist): ?>
                    <li><?= h($artist) ?></li>
                <?php endforeach; ?>
            </ul>
            <p class="hero-note"><?= h($home['invited_note']) ?></p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <h2 class="section-title">International readiness highlights</h2>
        <div class="feature-grid">
            <?php foreach ($highlights as $index => $item): ?>
                <article class="card">
                    <span class="icon-badge" aria-hidden="true">
                        <?php
                        $icon = $index % 3 === 0 ? 'document' : ($index % 3 === 1 ? 'shield' : 'building');
                        echo svg_icon($icon);
                        ?>
                    </span>
                    <h3><?= h($item['title']) ?></h3>
                    <?php if (!empty($item['content'])): ?><p><?= h($item['content']) ?></p><?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
    (function () {
        const toggleButton = document.getElementById('toggle-invited-artists');
        const panel = document.getElementById('invited-artists-panel');
        if (!toggleButton || !panel) return;

        toggleButton.addEventListener('click', function () {
            const isExpanded = toggleButton.getAttribute('aria-expanded') === 'true';
            toggleButton.setAttribute('aria-expanded', String(!isExpanded));
            panel.hidden = isExpanded;
            toggleButton.textContent = isExpanded ? 'See more' : 'See less';
        });
    })();
</script>
<?php render_footer(); ?>
