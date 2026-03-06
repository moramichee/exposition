<?php

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

$venue = app_content()['venue'];

render_header('venue', 'Global Alliance Exhibition | Venue Information');
?>
<section class="section">
    <div class="container">
        <p class="eyebrow">Venue Information</p>
        <h1 class="page-title">ExCel London Hotel, London</h1>

        <div class="split">
            <?php foreach ($venue['info_cards'] as $index => $item): ?>
                <article class="card">
                    <span class="icon-badge" aria-hidden="true"><?= svg_icon($index % 2 === 0 ? 'location' : 'calendar') ?></span>
                    <h2><?= h($item['title']) ?></h2>
                    <p><?= h($item['content']) ?></p>
                    <?php if (!empty($item['secondary_content'])): ?><p><?= h($item['secondary_content']) ?></p><?php endif; ?>
                    <?php if (!empty($item['extra_content'])): ?><p><?= h($item['extra_content']) ?></p><?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="map-wrap">
            <iframe
                title="ExCel London location map"
                src="https://www.google.com/maps?q=ExCeL+London&output=embed"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                allowfullscreen>
            </iframe>
        </div>
    </div>
</section>

<section class="section section-muted">
    <div class="container">
        <h2 class="section-title">Transport and accommodation support</h2>
        <div class="feature-grid">
            <?php foreach ($venue['support_cards'] as $index => $item): ?>
                <article class="card">
                    <?php
                    $iconCycle = ['plane', 'hotel', 'pulse'];
                    ?>
                    <span class="icon-badge" aria-hidden="true"><?= svg_icon($iconCycle[$index % 3]) ?></span>
                    <h3><?= h($item['title']) ?></h3>
                    <p><?= h($item['content']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php render_footer(); ?>
