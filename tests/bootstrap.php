<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

// @phpstan-ignore-next-line
if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

// Run migrations via CLI
passthru('php bin/console doctrine:migrations:migrate --no-interaction --quiet');
