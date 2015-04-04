<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim;

$app->view(new \Slim\Views\Twig());

$app->get('/', function() use ($app) {
    $app->render('viewer.html', [
      'character' => $app->request()->get('character'),
      'server' => $app->request()->get('server'),
    ]);
});

$app->run();
