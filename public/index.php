<?php 
ini_set('display_errors', true);

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../vendor/autoload.php';

$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

require __DIR__ . '/../src/dependencies.php';

require __DIR__ . '/../src/routes.php';

$app->redirect('/', '/api', 301);

$app->run();
