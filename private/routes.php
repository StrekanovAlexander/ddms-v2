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

  $this->get('/admin/posts', 'PostController:index')->setName('admin.posts');
  $this->get('/admin/post/details[/{id}]', 'PostController:details')->setName('admin.post.details');
  
  $this->get('/admin/post/create', 'PostController:getCreate')->setName('admin.post.create');
  $this->post('/admin/post/create', 'PostController:postCreate');
  
  $this->get('/admin/post/update[/{id}]', 'PostController:getUpdate')->setName('admin.post.update');
  $this->post('/admin/post/update', 'PostController:postUpdate');

  $this->get('/admin/post/files[/{id}]', 'PostController:files')->setName('admin.post.files');

  $this->post('/admin/post/upload', 'PostController:postUpload')->setName('admin.post.upload');
  $this->get('/admin/post/file/remove[/{id}/{file}/{main}]', 'PostController:removeFile')->setName('admin.post.file.remove');


  $this->get('/admin/fund', 'FundController:index')->setName('admin.fund');

  $this->get('/admin/fund/details[/{id}]', 'FundController:details')->setName('admin.fund.details');
  
  $this->get('/admin/fund/update[/{id}]', 'FundController:getUpdate')->setName('admin.fund.update');
  $this->post('/admin/fund/update', 'FundController:postUpdate');
  
  $this->get('/admin/fund/create', 'FundController:getCreate')->setName('admin.fund.create');
  $this->post('/admin/fund/create', 'FundController:postCreate');

  $this->post('/admin/fund/upload', 'FundController:postUpload')->setName('admin.fund.upload');

  $this->get('/admin/fund/file/remove[/{id}/{file}]', 'FundController:removeFile')->setName('admin.fund.file.remove');

})->add(new \App\Middleware\AuthMiddleware($container));

$app->group('', function() {
  $this->get('/admin/users', 'UserController:index')->setName('admin.users');
  $this->get('/admin/user/create', 'UserController:getCreate')->setName('admin.user.create');
  $this->post('/admin/user/create', 'UserController:postCreate');
  
  $this->get('/admin/user/update[/{id}]', 'UserController:getUpdate')->setName('admin.user.update');
  $this->post('/admin/user/update', 'UserController:postUpdate');

  $this->get('/admin/teachers', 'TeacherController:index')->setName('admin.teachers');

  $this->get('/admin/teacher/create', 'TeacherController:getCreate')->setName('admin.teacher.create');
  $this->post('/admin/teacher/create', 'TeacherController:postCreate');

  
  $this->get('/admin/teacher/update[/{id}]', 'TeacherController:getUpdate')->setName('admin.teacher.update');
  $this->post('/admin/teacher/update', 'TeacherController:postUpdate');
  
  $this->get('/admin/teacher/details[/{id}]', 'TeacherController:details')->setName('admin.teacher.details');

  $this->post('/admin/teacher/upload', 'TeacherController:postUpload')->setName('admin.teacher.upload');
  $this->get('/admin/teacher/file/remove[/{id}/{file}/{field}]', 'TeacherController:removeFile')->setName('admin.teacher.file.remove');

  $this->get('/admin/awards', 'AwardController:index')->setName('admin.awards');

  $this->get('/admin/award/details[/{id}]', 'AwardController:details')->setName('admin.award.details');
  
  $this->get('/admin/award/update[/{id}]', 'AwardController:getUpdate')->setName('admin.award.update');
  $this->post('/admin/award/update', 'AwardController:postUpdate');
  
  $this->get('/admin/award/create', 'AwardController:getCreate')->setName('admin.award.create');
  $this->post('/admin/award/create', 'AwardController:postCreate');

  $this->post('/admin/award/upload', 'AwardController:postUpload')->setName('admin.award.upload');

  $this->get('/admin/award/file/remove[/{id}/{file}]', 'AwardController:removeFile')->setName('admin.award.file.remove');

  
})->add(new \App\Middleware\AdminMiddleware($container));