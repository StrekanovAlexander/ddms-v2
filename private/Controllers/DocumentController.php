<?php
namespace App\Controllers;

use \App\Common\Files;
use \App\Common\Pages;
use \App\Models\Document;
use \Respect\Validation\Validator;

class DocumentController extends Controller {

  public function documents($request, $response) {

    return $this->view->render($response, 'guest/documents.twig', [
      'documents' => Document::where('is_actual', true)->get(),
      'activePage' => 'documents',
      'breadcrumbs' => Pages::breadcrumbs([
        ['Нормативні документи'],
      ]),
    ]);

  }

  public function index($request, $response) {
    $documents = Document::get();

    return $this->view->render($response, 'admin/document/index.twig', [
      'documents' => $documents,
      'breadcrumbs' => Pages::breadcrumbs([
        ['Нормативні документи'],
      ], true),
    ]);

  }

  public function details($request, $response, $args) {
    $document = Document::find($args['id']);

    $file = Files::getPath([
      $this->abspath, 'public', 'docs', $document->src
    ]);


    return $this->view->render($response, 'admin/document/details.twig', [
      'document' => $document,
      'file' => file_exists($file) && $document->src ? $file : null,
      'breadcrumbs' => Pages::breadcrumbs([
        ['Нормативні документи', 'admin.documents'],
        [$document->title],
      ], true)
    ]);      
  }

  public function getUpdate($request, $response, $args) {
    $document = Document::find($args['id']);
    return $this->view->render($response, 'admin/document/update.twig', [
      'document' => $document,
      'breadcrumbs' => Pages::breadcrumbs([
        ['Нормативні документи', 'admin.documents'],
        [$document->title],
        ['Редагування'],
      ], true)
    ]);      
  }

  public function postUpdate($request, $response) {

    $validation = $this->validator->validate($request, [
      'title' => Validator::notVoid(),
    ]);

    if ($validation->failed()) {
      $this->flash->addMessage('message', 'Помилка редагування документу');
      return $response->withRedirect($this->router->pathFor('admin.document.update', [
        'id' => $request->getParam('id'),
      ]));
    }

    $document = Document::find($request->getParam('id'));
    $document->update([
      'title' => $request->getParam('title'),
      'src' => $request->getParam('src'),
      'is_remote' => $request->getParam('is_remote')  ? true : false,
      'is_actual' => $request->getParam('is_actual')  ? true : false,
      'user_id' => $this->auth->user()->id,
    ]);

    $this->flash->addMessage('message', 'Документ було відредаговано');

    return $response->withRedirect($this->router->pathFor('admin.document.details', [
      'id' => $document->id,
    ]));

  }

  public function postUpload($request, $response) {
    $document = Document::find($request->getParam('id'));

    $path = Files::getPath([
      $this->abspath, 'public', 'docs'
    ]);

    $files = $request->getUploadedFiles();
    $file = $files['file'];
    $fileName = Files::moveFileRandomName($file, $path);

    if ($fileName) {
      $document->src = $fileName;
      $document->user_id = $this->auth->user()->id;
      $document->save();
      $this->flash->addMessage('message', 'Зображення було завантажено');   
    } else {
       $this->flash->addMessage('message', 'Помилка завантаження файлу!'); 
    }
    return $response->withRedirect($this->router->pathFor('admin.document.details', [
      'id' => $document->id,    
    ]));
  }

  public function getCreate($request, $response) {
    return $this->view->render($response, 'admin/document/create.twig', [
      'breadcrumbs' => Pages::breadcrumbs([
        ['Нормативні документи', 'admin.documents'],
        ['Створення'],
      ], true)
    ]);      
  }

  public function postCreate($request, $response) {

    $validation = $this->validator->validate($request, [
      'title' => Validator::notVoid(),
    ]);

    if ($validation->failed()) {
      $this->flash->addMessage('message', 'Помилка створення документу');
      return $response->withRedirect($this->router->pathFor('admin.document.create'));
    }

    Document::create([
      'title' => $request->getParam('title'),
      'src' => $request->getParam('src'),
      'is_remote' => $request->getParam('is_remote')  ? true : false,
      'is_actual' => $request->getParam('is_actual')  ? true : false,
      'user_id' => $this->auth->user()->id,
    ]);

    $id = Document::max('id');

    $this->flash->addMessage('message', 'Документ було створено');

    return $response->withRedirect($this->router->pathFor('admin.document.details', [
      'id' => $id,
    ]));

  }

  public function removeFile($request, $response, $args) {
    $file = Files::getPath([
      $this->abspath, 'public', 'docs', $args['file']
    ]);

    if (unlink($file)) {
      $this->flash->addMessage('message', 'Файл було видалено');
      $document = Document::find($args['id']);
      $document->src = null;
      $document->save();
    } else {
      $this->flash->addMessage('message', 'Помилка видалення файлу');
    }
    
    return $response->withRedirect($this->router->pathFor('admin.document.details', [
      'id' => $args['id'], 
    ]));
    
  }


}