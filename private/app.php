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

$container['abspath'] = function() {
  return dirname(dirname(__FILE__));
};

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

$container['AudioController'] = function($container) {
  return new \App\Controllers\AudioController($container);
};

$container['AwardController'] = function($container) {
  return new \App\Controllers\AwardController($container);
};

$container['CarouselController'] = function($container) {
  return new \App\Controllers\CarouselController($container);
};

$container['DepartmentController'] = function($container) {
  return new \App\Controllers\DepartmentController($container);
};

$container['DocumentController'] = function($container) {
  return new \App\Controllers\DocumentController($container);
};

$container['FundController'] = function($container) {
  return new \App\Controllers\FundController($container);
};

$container['PageController'] = function($container) {
  return new \App\Controllers\PageController($container);
};

$container['ParentController'] = function($container) {
  return new \App\Controllers\ParentController($container);
};

$container['PostController'] = function($container) {
  return new \App\Controllers\PostController($container);
};

$container['StudentController'] = function($container) {
  return new \App\Controllers\StudentController($container);
};

$container['TeacherController'] = function($container) {
  return new \App\Controllers\TeacherController($container);
};

$container['UserController'] = function($container) {
  return new \App\Controllers\UserController($container);
};

$container['notFoundHandler'] = function($container) {
	return function($request, $response) use ($container) {
    return $container->view->render($response, '404.twig')->withStatus(404);
	};
};

$app->add(new \App\Middleware\CsrfMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\ValidationMiddleware($container));
$app->add($container->csrf);

Validator::with('App\\Validation\\Rules\\');

require __DIR__ . '/routes.php';