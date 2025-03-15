<?php
require __DIR__ . '/vendor/autoload.php';

if (class_exists('App\Http\Middleware\RoleMiddleware')) {
    echo "RoleMiddleware exists.\n";
} else {
    echo "RoleMiddleware does not exist.\n";
}
