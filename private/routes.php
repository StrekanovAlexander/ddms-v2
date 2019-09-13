<?php

$app->get('/', 'PageController:home')->setName('home');
$app->get('/teachers', 'TeacherController:teachers')->setName('teachers');
$app->get('/teacher[/{id}]', 'TeacherController:teacher')->setName('teacher');

$app->group('', function() {
  $this->get('/admin/user/login', 'UserController:getLogin')->setName('admin.user.login');
  $this->post('/admin/user/login', 'UserController:postLogin');
})->add(new \App\Middleware\UserMiddleware($container));

$app->group('', function() {
  $this->get('/admin/user/logout', 'UserController:logout')->setName('admin.user.logout');
  $this->get('/admin/dashboard', 'PageController:dashboard')->setName('admin.dashboard');
})->add(new \App\Middleware\AuthMiddleware($container));

$app->group('', function() {
  $this->get('/admin/users', 'UserController:index')->setName('admin.users');
  $this->get('/admin/user/create', 'UserController:getCreate')->setName('admin.user.create');
  $this->post('/admin/user/create', 'UserController:postCreate');
  $this->get('/admin/user/details[/{id}]', 'UserController:details')->setName('admin.user.details');

  $this->get('/admin/user/update[/{id}]', 'UserController:getUpdate')->setName('admin.user.update');
  $this->post('/admin/user/update', 'UserController:postUpdate');

})->add(new \App\Middleware\AdminMiddleware($container));