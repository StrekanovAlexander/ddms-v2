<?php

session_start();

use \Respect\Validation\Validator;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/db.php';

$app = new \Slim\App([
  'settings' => [
    'displayErrorDetails' => true,
  ], 
]);

$container = $app->getContainer();

$capsule = \App\Common\Capsule::capsule($db);

$container['auth'] = function() {
  return new \App\Common\Auth;
};

$container['csrf'] = function() {
  return new \Slim\Csrf\Guard;
};

$container['db'] = function() use ($capsule) {
  return $capsule;
};

$container['flash'] = function() {
  return new \Slim\Flash\Messages;
};

$container['validator'] = function() {
  return new App\Validation\Validator;
};

$container['view'] = function($container) {
  
  $view = new \Slim\Views\Twig(__DIR__ . '/views', ['cache' => false]);
  
  $view->addExtension(new \Slim\Views\TwigExtension(
    $container->router,
    $container->request->getUri()
  ));

  $view->getEnvironment()->addGlobal('flash', $container->flash);

  $view->getEnvironment()->addGlobal('auth', [
    'isAuth' => $container->auth->isAuth(),
    'user' => $container->auth->user(),
  ]);

  return $view;
};

$container['AwardController'] = function($container) {
  return new \App\Controllers\AwardController($container);
};

$container['PageController'] = function($container) {
  return new \App\Controllers\PageController($container);
};

$container['ParentController'] = function($container) {
  return new \App\Controllers\ParentController($container);
};

$container['UserController'] = function($container) {
  return new \App\Controllers\UserController($container);
};

$container['TeacherController'] = function($container) {
  return new \App\Controllers\TeacherController($container);
};

$app->add(new \App\Middleware\CsrfMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\ValidationMiddleware($container));
$app->add($container->csrf);

Validator::with('App\\Validation\\Rules\\');

require __DIR__ . '/routes.php';