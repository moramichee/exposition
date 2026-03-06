<?php

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

[$errors, $old, $status] = process_registration_form();
$contact = app_content()['contact'];

render_header('contact', 'Global Alliance Exhibition | Contact & Registration');
?>
<section class="section">
    <div class="container">
        <p class="eyebrow">Contact</p>
        <h1 class="page-title">Registration and direct contact</h1>

        <div class="split">
            <?php foreach ($contact['channel_cards'] as $item): ?>
                <article class="card">
                    <span class="icon-badge" aria-hidden="true"><?= svg_icon('contact') ?></span>
                    <h2><?= h($item['title']) ?></h2>
                    <?php $email = trim(str_ireplace('Email:', '', $item['content'])); ?>
                    <?php $phone = trim(str_ireplace('WhatsApp:', '', $item['secondary_content'])); ?>
                    <?php $phoneDigits = preg_replace('/[^0-9]/', '', $phone) ?: ''; ?>
                    <p><strong>Email:</strong> <a href="mailto:<?= h($email) ?>"><?= h($email) ?></a></p>
                    <p><strong>WhatsApp:</strong> <a href="https://wa.me/<?= h($phoneDigits) ?>" target="_blank" rel="noopener"><?= h($phone) ?></a></p>
                    <p><strong>Partners:</strong> <?= h(str_ireplace('Partners:', '', $item['extra_content'])) ?></p>
                    <p>For faster processing, submit the form and confirm via WhatsApp.</p>
                </article>
            <?php endforeach; ?>

            <?php foreach ($contact['terms_cards'] as $item): ?>
                <article class="card">
                    <span class="icon-badge" aria-hidden="true"><?= svg_icon('calendar') ?></span>
                    <h2><?= h($item['title']) ?></h2>
                    <ul class="list">
                        <?php foreach (multiline_lines($item['extra_content']) as $line): ?>
                            <li><?= h($line) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </article>
            <?php endforeach; ?>
        </div>

        <?php if ($status !== null): ?>
            <div class="alert-success" role="status"><?= h($status) ?></div>
        <?php endif; ?>

        <article class="card form-card">
            <span class="icon-badge" aria-hidden="true"><?= svg_icon('document') ?></span>
            <h2>Registration form</h2>
            <form method="post" action="contact.php" novalidate>
                <div class="form-grid">
                    <label>
                        Full name
                        <input type="text" name="full_name" value="<?= h($old['full_name']) ?>" required>
                        <?php if (isset($errors['full_name'])): ?><small class="error"><?= h($errors['full_name']) ?></small><?php endif; ?>
                    </label>

                    <label>
                        Country and city
                        <input type="text" name="country_city" value="<?= h($old['country_city']) ?>" required>
                        <?php if (isset($errors['country_city'])): ?><small class="error"><?= h($errors['country_city']) ?></small><?php endif; ?>
                    </label>

                    <label>
                        Types of artworks
                        <input type="text" name="artwork_types" value="<?= h($old['artwork_types']) ?>" required>
                        <?php if (isset($errors['artwork_types'])): ?><small class="error"><?= h($errors['artwork_types']) ?></small><?php endif; ?>
                    </label>

                    <label>
                        Gender
                        <select name="gender" required>
                            <option value="">Select one</option>
                            <?php foreach (['Female', 'Male', 'Non-binary', 'Prefer not to say'] as $gender): ?>
                                <option value="<?= h($gender) ?>" <?= $old['gender'] === $gender ? 'selected' : '' ?>><?= h($gender) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['gender'])): ?><small class="error"><?= h($errors['gender']) ?></small><?php endif; ?>
                    </label>

                    <label>
                        Email
                        <input type="email" name="email" value="<?= h($old['email']) ?>" required>
                        <?php if (isset($errors['email'])): ?><small class="error"><?= h($errors['email']) ?></small><?php endif; ?>
                    </label>

                    <label>
                        WhatsApp number
                        <input type="text" name="whatsapp" value="<?= h($old['whatsapp']) ?>" required>
                        <?php if (isset($errors['whatsapp'])): ?><small class="error"><?= h($errors['whatsapp']) ?></small><?php endif; ?>
                    </label>
                </div>

                <button class="btn btn-primary" type="submit">Submit Registration</button>
            </form>
        </article>
    </div>
</section>
<?php render_footer(); ?>
