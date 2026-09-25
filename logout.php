<?php
declare(strict_types=1);

require __DIR__ . '/include/bootstrap.php';

// Logging out is a POST with CSRF token (the header button); GET only redirects.
if (is_post()) {
    Auth::logout();
}
redirect('index.php');
