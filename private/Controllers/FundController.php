<?php
namespace App\Controllers;

use \App\Common\Files;
use \App\Common\Pages;
use \App\Models\Fund;
use \App\Models\Section;
use \Respect\Validation\Validator;

class FundController extends Controller {

    public function fund($request, $response) {
      $fund = Fund::where('is_actual', true)->get()->toArray();
      return $this->view->render($response, 'guest/fund.twig', [
        'fund' => Pages::pagination($fund, $request->getParam('page', 1), 12),
        'breadcrumbs' => Pages::breadcrumbs([
           ['Золотий фонд художнього відділу'],
        ]),
      ]);
    }

    public function index($request, $response) {
      $fund = Fund::fund()->toArray();
      return $this->view->render($response, 'admin/fund/index.twig', [
        'fund' => Pages::pagination($fund, $request->getParam('page', 1), 5),
        'breadcrumbs' => Pages::breadcrumbs([
          ['Золотий фонд'],
        ], true),
      ]);
    }

  public function details($request, $response, $args) {
    $fund = Fund::find($args['id']);
    return $this->view->render($response, 'admin/fund/details.twig', [
      'fund' => $fund,
      'breadcrumbs' => Pages::breadcrumbs([
        ['Золотий фонд', 'admin.fund'],
        [$fund->title],
        ], true),
    ]);
  }

  public function getUpdate($request, $response, $args) {
    $fund = Fund::find($args['id']);
    return $this->view->render($response, 'admin/fund/update.twig', [
      'fund' => $fund,
      'breadcrumbs' => Pages::breadcrumbs([
        ['Золотий фонд', 'admin.fund'],
        [$fund->title],
      ], true),
    ]);
  }

    public function postUpdate($request, $response) {

      $validation = $this->validator->validate($request, [
        'title' => Validator::notVoid(),
      ]);
  
      if ($validation->failed()) {
        $this->flash->addMessage('message', 'Помилка редагування запису');
        return $response->withRedirect($this->router->pathFor('admin.fund.update', [
          'id' => $request->getParam('id'),
        ]));
      }
  
      $fund = Fund::find($request->getParam('id'));
      $fund->update([
        'section_id' => $request->getParam('section_id'),
        'title' => $request->getParam('title'),
        'content' => $request->getParam('content'),
        'is_actual' => $request->getParam('is_actual')  ? true : false,
        'user_id' => $this->auth->user()->id,
      ]);
  
      $this->flash->addMessage('message', 'Запис було відредаговано');
  
      return $response->withRedirect($this->router->pathFor('admin.fund.details', [
        'id' => $fund->id,
      ]));
    
    }
    
    public function getCreate($request, $response) {
      return $this->view->render($response, 'admin/fund/create.twig', [
        'breadcrumbs' => Pages::breadcrumbs([
          ['Золотий фонд', 'admin.fund'],
          ['Створити'],
        ], true),
      ]);
    }

    public function postCreate($request, $response) {

      $validation = $this->validator->validate($request, [
        'title' => Validator::notVoid(),
      ]);
  
      if ($validation->failed()) {
        $this->flash->addMessage('message', 'Помилка створення зaпису');
        return $response->withRedirect($this->router->pathFor('admin.fund.create'));
      }

        
      Fund::create([
        'section_id' => $request->getParam('section_id'),
        'title' => $request->getParam('title'),
        'content' => $request->getParam('content'),
        'is_actual' => $request->getParam('is_actual')  ? true : false,
        'user_id' => $this->auth->user()->id,
      ]);

      $id = Fund::max('id');

      $this->flash->addMessage('message', 'Запис було створено');
  
      return $response->withRedirect($this->router->pathFor('admin.fund.details', [
        'id' => $id,
      ]));

    }

    public function postUpload($request, $response) {
      $fund = Fund::find($request->getParam('id'));
  
      $path = Files::getPath([
        $this->abspath, 'public', 'images', 'fund'
      ]);
  
      $files = $request->getUploadedFiles();
      $file = $files['file'];
      $fileName = Files::moveFileRandomName($file, $path);
  
      if ($fileName) {
        $fund->image = $fileName;
        $fund->user_id = $this->auth->user()->id;
        $fund->save();
        $this->flash->addMessage('message', 'Зображення було завантажено');   
      } else {
         $this->flash->addMessage('message', 'Помилка завантаження файлу!'); 
      }
      return $response->withRedirect($this->router->pathFor('admin.fund.details', [
        'id' => $fund->id,    
      ]));
    }

  public function removeFile($request, $response, $args) {
      $file = Files::getPath([
        $this->abspath, 'public', 'images', 'fund', $args['file']
      ]);
  
      if (unlink($file)) {
        $this->flash->addMessage('message', 'Файл було видалено');
        $fund = Fund::find($args['id']);
        $fund->image = null;
        $fund->save();
      } else {
        $this->flash->addMessage('message', 'Помилка видалення файлу');
      }
      
      return $response->withRedirect($this->router->pathFor('admin.fund.details', [
        'id' => $args['id'], 
      ]));
      
  }
  

}