<?php

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

$press = app_content()['press'];

render_header('press', 'Global Alliance Exhibition | Press & Media');
?>
<section class="section">
    <div class="container">
        <p class="eyebrow">Press & Media</p>
        <h1 class="page-title">Media visibility and credibility assets</h1>

        <div class="partner-grid">
            <?php foreach ($press['partner_cards'] as $index => $item): ?>
                <article class="partner-card">
                    <span class="icon-badge" aria-hidden="true"><?= svg_icon($index % 2 === 0 ? 'plane' : 'shield') ?></span>
                    <span class="partner-logo"><?= h($item['title']) ?></span>
                    <p><?= h($item['content']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="split">
            <?php foreach ($press['resource_cards'] as $index => $item): ?>
                <?php $lines = multiline_lines($item['extra_content'] ?? null); ?>
                <article class="card">
                    <span class="icon-badge" aria-hidden="true"><?= svg_icon($index % 2 === 0 ? 'document' : 'shield') ?></span>
                    <h2><?= h($item['title']) ?></h2>
                    <?php if (!empty($item['content'])): ?><p><?= h($item['content']) ?></p><?php endif; ?>
                    <?php if ($lines !== []): ?>
                        <ul class="list">
                            <?php foreach ($lines as $line): ?>
                                <li><?= h($line) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <?php if (stripos($item['title'], 'download') !== false): ?>
                        <a class="btn btn-primary" href="press-release.php">Download Press Release (PDF)</a>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php render_footer(); ?>
