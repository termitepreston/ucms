<?php

declare(strict_types=1);

require '../vendor/autoload.php';

use MicroCMS\Application as Application;

define('UPLOAD_DIR', __DIR__ . '/uploads/');


$app = (new Application())->run();
