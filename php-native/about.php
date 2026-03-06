<?php

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

$about = app_content()['about'];
$missionCards = active_items($about['mission_cards']);
$biographyCards = active_items($about['biography_cards']);

render_header('about', 'Global Alliance Exhibition | About');
?>
<section class="section">
    <div class="container">
        <p class="eyebrow">About the Exhibition</p>
        <h1 class="page-title">Why this exhibition exists</h1>

        <div class="split">
            <?php foreach ($missionCards as $index => $item): ?>
                <article class="card">
                    <span class="icon-badge" aria-hidden="true">
                        <?php
                        $iconCycle = ['document', 'wave', 'arrow-up', 'check'];
                        echo svg_icon($iconCycle[$index % 4]);
                        ?>
                    </span>
                    <h2><?= h($item['title']) ?></h2>
                    <p><?= h($item['content']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section-muted">
    <div class="container">
        <h2 class="section-title">Organizer biography</h2>
        <?php foreach ($biographyCards as $item): ?>
            <article class="card">
                <span class="icon-badge" aria-hidden="true"><?= svg_icon('user') ?></span>
                <h3><?= h($item['title']) ?></h3>
                <p><?= h($item['content']) ?></p>
                <?php if (!empty($item['extra_content'])): ?><p><?= h($item['extra_content']) ?></p><?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php render_footer(); ?>
