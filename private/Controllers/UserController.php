<?php
namespace App\Controllers;

use \App\Common\Pages;
use \App\Models\User;
use \Respect\Validation\Validator;

class UserController extends Controller {

  public function index($request, $response) {
    $users = User::get();
    return $this->view->render($response, 'admin/user/index.twig', [
      'users' => $users,
      'breadcrumbs' => Pages::breadcrumbs([
        ['Користувачи']
      ]),  
    ]);
  }

  public function getCreate($request, $response) {
    return $this->view->render($response, 'admin/user/create.twig');
  }

  public function postCreate($request, $response) {

    $validation = $this->validator->validate($request, [
      'username' => Validator::notVoid()->noSpaces()->available('username', User::class),
      'password' => Validator::notVoid()->noSpaces(),
      'password2' => Validator::notVoid()->noSpaces()->compare($request->getParam('password'))
    ]);

    if ($validation->failed()) {

      $this->flash->addMessage('message', 'Error user creating');

      return $response->withRedirect($this->router->pathFor('admin.user.create'));
    }

    User::add($request->getParams());

    $this->flash->addMessage('message', 'The user account was created successful');

    return $response->withRedirect($this->router->pathFor('admin.users', [
      'breadcrumbs' => Pages::breadcrumbs(),  
    ]));
  }

  public function getLogin($request, $response) {
    return $this->view->render($response, 'admin/user/login.twig', [
      'breadcrumbs' => Pages::breadcrumbs(),
    ]);
  }

  public function postLogin($request, $response) {
    
    $auth = $this->auth->auth(
      $request->getParam('username'),
      $request->getParam('password')
    );  

    if (!$auth) {
      $this->flash->addMessage('message', 'Login error');
      return $response->withRedirect($this->router->pathFor('admin.user.login'));
    }

    return $response->withRedirect($this->router->pathFor('admin.dashboard', [
      'breadcrumbs' => Pages::breadcrumbs(),
    ]));


    return $response->withRedirect($this->router->pathFor('admin.event.details', [
      'id' => $request->getParam('id'),
    ]));

  }

  public function logout($request, $response) {
    $this->auth->logout();
    return $response->withRedirect($this->router->pathFor('home'));
  }

  public function details($request, $response, $args) {

    return $this->view->render($response, 'admin/user/details.twig', [
      'user' => $this->auth->user(),
    ]);

  }

  public function getUpdate($request, $response, $args) {
    return $this->view->render($response, 'admin/user/update.twig', [
      'user' => User::find($args['id']),
    ]);
  }

  public function postUpdate($request, $response) {

    if ($request->getParam('edit_password')) {
      
      $validation = $this->validator->validate($request, [
        'password' => Validator::notVoid()->noSpaces(),
        'password2' => Validator::notVoid()->noSpaces()->compare($request->getParam('password'))
      ]);

      if ($validation->failed()) {
        $this->flash->addMessage('message', 'Помилка зміни паролю');
        return $response->withRedirect($this->router->pathFor('admin.user.update', [
          'id' => $request->getParam('id')
        ]));
      }

    }

    $user = User::find($request->getParam('id'));

    if ($request->getParam('edit_password')) {
      $user->update([
        'password' => User::setPassword($request->getParam('password')),
      ]);
    }  

    $user->update([
      'is_actual' => $request->getParam('is_actual') ? true : false,
    ]);

    $this->flash->addMessage('message', 'Дані користувача були змінені');

    return $response->withRedirect($this->router->pathFor('admin.users'));
  }
  
}