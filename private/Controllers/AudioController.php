<?php
namespace App\Controllers;

use \App\Common\Files;
use \App\Common\Pages;
use \App\Models\Audio;
use \Respect\Validation\Validator;

class AudioController extends Controller {

  public function minus($request, $response) {

    $audio = Audio::where('is_actual', true)->get();
    return $this->view->render($response, 'guest/minus.twig', [
      'audio' => $audio,
      'breadcrumbs' => Pages::breadcrumbs([
        ['Мінусові фонограми']
      ]),
    ]);

  }

  public function index($request, $response) {

    $audio = Audio::get();
    return $this->view->render($response, 'admin/audio/index.twig', [
      'audio' => $audio,
      'breadcrumbs' => Pages::breadcrumbs([
        ['Мінусові фонограми']
      ], true),
    ]);

  }

  public function details($request, $response, $args) {
    $audio = Audio::find($args['id']);
    $file = Files::getPath([
      $this->abspath, 'public', 'audio', $audio->filename
    ]);
    
    return $this->view->render($response, 'admin/audio/details.twig', [
      'audio' => $audio,
      'file' => file_exists($file) && $audio->filename ? $file : null,
      'breadcrumbs' => Pages::breadcrumbs([
        ['Мінусові фонограми', 'admin.audio'],
        [$audio->title],
      ], true),
    ]);
}

public function getUpdate($request, $response, $args) {
  $audio = audio::find($args['id']);
  return $this->view->render($response, 'admin/audio/update.twig', [
    'audio' => $audio,
      'breadcrumbs' => Pages::breadcrumbs([
      ['Мінусові фонограми', 'admin.audio'],
      [$audio->title, 'admin.audio.details', $audio->id],
      ['Редагування'],
    ], true),
  ]);
}

public function postUpdate($request, $response) {

  $validation = $this->validator->validate($request, [
    'title' => Validator::notVoid(),
  ]);

  if ($validation->failed()) {
    $this->flash->addMessage('message', 'Помилка редагування фонограми');
    return $response->withRedirect($this->router->pathFor('admin.audio.update', [
      'id' => $request->getParam('id'),
    ]));
  }

  $audio = Audio::find($request->getParam('id'));
  $audio->update([
    'title' => $request->getParam('title'),
    'content' => $request->getParam('content'),
    'is_actual' => $request->getParam('is_actual')  ? true : false,
    'user_id' => $this->auth->user()->id,
  ]);

  $this->flash->addMessage('message', 'Фонограму було відредаговано');

  return $response->withRedirect($this->router->pathFor('admin.audio.details', [
    'id' => $audio->id,
  ]));

}

public function getCreate($request, $response) {
  return $this->view->render($response, 'admin/audio/create.twig', [
    'breadcrumbs' => Pages::breadcrumbs([
      ['Мінусові фонограми', 'admin.audio'],
      ['Створити'],
    ], true),
  ]);
}

public function postCreate($request, $response) {

  $validation = $this->validator->validate($request, [
    'title' => Validator::notVoid(),
  ]);

  if ($validation->failed()) {
    $this->flash->addMessage('message', 'Помилка створення фонограми');
    return $response->withRedirect($this->router->pathFor('admin.audio.create'));
  }
    
  Audio::create([
    'title' => $request->getParam('title'),
    'content' => $request->getParam('content'),
    'is_actual' => $request->getParam('is_actual')  ? true : false,
    'user_id' => $this->auth->user()->id,
  ]);

  $id = Audio::max('id');

  $this->flash->addMessage('message', 'Фонограму було створено');

  return $response->withRedirect($this->router->pathFor('admin.audio.details', [
    'id' => $id,
  ]));

}

public function postUpload($request, $response) {
  $audio = Audio::find($request->getParam('id'));

  $path = Files::getPath([
    $this->abspath, 'public', 'audio'
  ]);

  $files = $request->getUploadedFiles();
  $file = $files['file'];
  $fileName = Files::moveFileRandomName($file, $path);

  if ($fileName) {
    $audio->filename = $fileName;
    $audio->user_id = $this->auth->user()->id;
    $audio->save();
    $this->flash->addMessage('message', 'Файл фонограми було завантажено');   
  } else {
     $this->flash->addMessage('message', 'Помилка завантаження файлу!'); 
  }
  return $response->withRedirect($this->router->pathFor('admin.audio.details', [
    'id' => $audio->id,    
  ]));
}

public function removeFile($request, $response, $args) {
  $file = Files::getPath([
    $this->abspath, 'public', 'audio', $args['file']
  ]);

  if (unlink($file)) {
    $this->flash->addMessage('message', 'Файл було видалено');
    $audio = Audio::find($args['id']);
    $audio->filename = null;
    $audio->save();
  } else {
    $this->flash->addMessage('message', 'Помилка видалення файлу');
  }
  
  return $response->withRedirect($this->router->pathFor('admin.audio.details', [
    'id' => $args['id'], 
  ]));
  }

}