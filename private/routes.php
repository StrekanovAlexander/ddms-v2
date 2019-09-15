<?php

$app->get('/', 'PageController:home')->setName('home');
$app->get('/contacts', 'PageController:contacts')->setName('contacts');

$app->get('/teachers', 'TeacherController:teachers')->setName('teachers');
$app->get('/teacher[/{id}]', 'TeacherController:teacher')->setName('teacher');

$app->get('/awards', 'AwardController:awards')->setName('awards');

$app->get('/parents', 'ParentController:parents')->setName('parents');
$app->get('/documents', 'DocumentController:documents')->setName('documents');

$app->get('/department[/{slug}]', 'DepartmentController:department')->setName('department');

$app->get('/minus', 'AudioController:minus')->setName('minus');

$app->get('/students[/{id}]', 'StudentController:students')->setName('students');
$app->get('/student[/{id}]', 'StudentController:student')->setName('student');

$app->get('/posts', 'PostController:posts')->setName('posts');
$app->get('/post[/{id}]', 'PostController:post')->setName('post');
$app->get('/processes', 'PostController:processes')->setName('processes');
$app->get('/process[/{id}]', 'PostController:process')->setName('process');

$app->get('/contests[/{id}]', 'PostController:contests')->setName('contests');
$app->get('/contest[/{id}]', 'PostController:contest')->setName('contest');

$app->get('/foundation', 'PostController:foundation')->setName('foundation');
$app->get('/archive', 'PostController:archive')->setName('archive');

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
  
  $this->get('/admin/user/update[/{id}]', 'UserController:getUpdate')->setName('admin.user.update');
  $this->post('/admin/user/update', 'UserController:postUpdate');

  $this->get('/admin/teachers', 'TeacherController:index')->setName('admin.teachers');
  $this->get('/admin/teacher/update[/{id}]', 'TeacherController:getUpdate')->setName('admin.teacher.update');
  $this->post('/admin/teacher/update', 'TeacherController:postUpdate');
  $this->get('/admin/teacher/details[/{id}]', 'TeacherController:details')->setName('admin.teacher.details');

})->add(new \App\Middleware\AdminMiddleware($container));