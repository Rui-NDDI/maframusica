<?php

/**
 * @file
 * Loads environment specific configuration.
 *
 * This file is included very early. See autoload.files in composer.json.
 * @see https://getcomposer.org/doc/04-schema.md#files
 */

declare(strict_types = 1);

use Dotenv\Dotenv;

/**
 * Load any .env file. See /.env.example.
 *
 * Drupal has no official method for loading environment variables and uses
 * getenv() in some places.
 */
$dotenv = Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->safeLoad();
