<?php

$app->get('/', 'PageController:home')->setName('home');
$app->get('/teachers', 'TeacherController:teachers')->setName('teachers');
$app->get('/teacher[/{id}]', 'TeacherController:teacher')->setName('teacher');

$app->get('/admin/dashboard', 'PageController:dashboard')->setName('admin.dashboard');
$app->get('/admin/users', 'UserController:index')->setName('admin.users');
$app->get('/admin/user/create', 'UserController:getCreate')->setName('admin.user.create');
$app->post('/admin/user/create', 'UserController:postCreate');

$app->get('/admin/user/login', 'UserController:getLogin')->setName('admin.user.login');
$app->post('/admin/user/login', 'UserController:postLogin');

$app->get('/admin/user/logout', 'UserController:logout')->setName('admin.user.logout');

$app->get('/admin/user/details[/{id}]', 'UserController:details')->setName('admin.user.details');