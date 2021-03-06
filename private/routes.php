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

$app->get('/fund', 'FundController:fund')->setName('fund');

$app->get('/remote/tasks[/{id}]', 'RemoteController:tasks')->setName('remote.tasks');
$app->get('/remote/task[/{id}]', 'RemoteController:task')->setName('remote.task');

$app->get('/remote[/{id}]', 'RemoteController:index')->setName('remote.index');



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


  $this->get('/admin/students', 'StudentController:index')->setName('admin.students');

  $this->get('/admin/student/details[/{id}]', 'StudentController:details')->setName('admin.student.details');
  
  $this->get('/admin/student/update[/{id}]', 'StudentController:getUpdate')->setName('admin.student.update');
  $this->post('/admin/student/update', 'StudentController:postUpdate');
  
  $this->get('/admin/student/create', 'StudentController:getCreate')->setName('admin.student.create');
  $this->post('/admin/student/create', 'StudentController:postCreate');

  $this->post('/admin/student/upload', 'StudentController:postUpload')->setName('admin.student.upload');

  $this->get('/admin/student/file/remove[/{id}/{file}]', 'StudentController:removeFile')->setName('admin.student.file.remove');

  // Remote
  $this->get('/admin/remote/create[/{id}]', 'RemoteController:getCreate')->setName('admin.remote.create');
  $this->post('/admin/remote/create', 'RemoteController:postCreate');
  $this->get('/admin/remote/details/{id}', 'RemoteController:details')->setName('admin.remote.details');
  
  $this->get('/admin/remote/update[/{id}]', 'RemoteController:getUpdate')->setName('admin.remote.update');
  $this->post('/admin/remote/update', 'RemoteController:postUpdate');

  $this->get('/admin/remote/files[/{id}]', 'RemoteController:files')->setName('admin.remote.files');
  $this->post('/admin/remote/upload', 'RemoteController:postUpload')->setName('admin.remote.upload');
  $this->get('/admin/remote/file/remove[/{id}/{file}]', 'RemoteController:removeFile')->setName('admin.remote.file.remove');
  
  $this->get('/admin/remote[/{id}]', 'RemoteController:adminIndex')->setName('admin.remote');

  $this->get('/admin/subjects', 'SubjectController:index')->setName('admin.subjects');
  $this->get('/admin/subject/create', 'SubjectController:getCreate')->setName('admin.subject.create');
  $this->post('/admin/subject/create', 'SubjectController:postCreate');

  $this->get('/admin/subject/details[/{id}]', 'SubjectController:details')->setName('admin.subject.details');
  $this->get('/admin/subject/update[/{id}]', 'SubjectController:getUpdate')->setName('admin.subject.update');
  $this->post('/admin/subject/update', 'SubjectController:postUpdate');

  // Remote end

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

  $this->get('/admin/documents', 'DocumentController:index')->setName('admin.documents');

  $this->get('/admin/document/details[/{id}]', 'DocumentController:details')->setName('admin.document.details');

  $this->get('/admin/document/create', 'DocumentController:getCreate')->setName('admin.document.create');
  $this->post('/admin/document/create', 'DocumentController:postCreate');

  $this->get('/admin/document/update[/{id}]', 'DocumentController:getUpdate')->setName('admin.document.update');
  $this->post('/admin/document/update', 'DocumentController:postUpdate');

  $this->post('/admin/document/upload', 'DocumentController:postUpload')->setName('admin.document.upload');

  $this->get('/admin/document/file/remove[/{id}/{file}]', 'DocumentController:removeFile')->setName('admin.document.file.remove');

  $this->get('/admin/carousel', 'CarouselController:index')->setName('admin.carousel');

  $this->get('/admin/carousel/details[/{id}]', 'CarouselController:details')->setName('admin.carousel.details');

  $this->get('/admin/carousel/update[/{id}]', 'CarouselController:getUpdate')->setName('admin.carousel.update');
  $this->post('/admin/carousel/update', 'CarouselController:postUpdate');

  $this->post('/admin/carousel/upload', 'CarouselController:postUpload')->setName('admin.carousel.upload');

  $this->get('/admin/carousel/file/remove[/{id}/{file}]', 'CarouselController:removeFile')->setName('admin.carousel.file.remove');


  $this->get('/admin/audio', 'AudioController:index')->setName('admin.audio');

  $this->get('/admin/audio/details[/{id}]', 'AudioController:details')->setName('admin.audio.details');

  $this->get('/admin/audio/update[/{id}]', 'AudioController:getUpdate')->setName('admin.audio.update');
  $this->post('/admin/audio/update', 'AudioController:postUpdate');

  $this->post('/admin/audio/upload', 'AudioController:postUpload')->setName('admin.audio.upload');

  $this->get('/admin/audio/file/remove[/{id}/{file}]', 'AudioController:removeFile')->setName('admin.audio.file.remove');

  $this->get('/admin/audio/create', 'AudioController:getCreate')->setName('admin.audio.create');
  $this->post('/admin/audio/create', 'AudioController:postCreate');

  $this->get('/admin/accounts', 'AccountController:index')->setName('admin.accounts');
  $this->get('/admin/account/details[/{id}]', 'AccountController:details')->setName('admin.account.details');
  $this->get('/admin/account/create', 'AccountController:getCreate')->setName('admin.account.create');
  $this->post('/admin/account/create', 'AccountController:postCreate');

  $this->get('/admin/account/update[/{id}]', 'AccountController:getUpdate')->setName('admin.account.update');
  $this->post('/admin/account/update', 'AccountController:postUpdate');

  $this->post('/admin/account/upload', 'AccountController:postUpload')->setName('admin.account.upload');

  $this->get('/admin/account/file/remove[/{id}/{file}]', 'AccountController:removeFile')->setName('admin.account.file.remove');

  
  
})->add(new \App\Middleware\AdminMiddleware($container));