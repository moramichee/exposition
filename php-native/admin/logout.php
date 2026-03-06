<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/admin.php';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    admin_logout();
}

redirect_to('login.php');
