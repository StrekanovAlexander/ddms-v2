<?php
namespace App\Controllers;

use \App\Common\Files;
use \App\Common\Pages;
use \App\Models\Award;
use \App\Models\Section;
use \Respect\Validation\Validator;

class AwardController extends Controller {

    public function awards($request, $response) {
        $awards = \App\Models\Award::where('is_actual', true)->get()->toArray();
        return $this->view->render($response, 'guest/awards.twig', [
            'awards' => Pages::pagination($awards, $request->getParam('page', 1), 12),
            'activePage' => 'awards',
            'breadcrumbs' => Pages::breadcrumbs([
               ['Відзнаки'],
            ]),
        ]);
    }

    public function index($request, $response) {
        $awards = Award::awards()->toArray();
        return $this->view->render($response, 'admin/award/index.twig', [
            'awards' => Pages::pagination($awards, $request->getParam('page', 1), 5),
            'breadcrumbs' => Pages::breadcrumbs([
               ['Відзнаки'],
            ], true),
        ]);

    }

    public function details($request, $response, $args) {
        $award = Award::find($args['id']);
        return $this->view->render($response, 'admin/award/details.twig', [
          'award' => $award,
          'breadcrumbs' => Pages::breadcrumbs([
            ['Відзнаки', 'admin.awards'],
            [$award->title],
          ], true),
        ]);
    }

    public function getUpdate($request, $response, $args) {
      $award = Award::find($args['id']);
      $sections = Section::where('is_actual', true)->get();
      return $this->view->render($response, 'admin/award/update.twig', [
        'award' => $award,
        'sections' => $sections,
        'breadcrumbs' => Pages::breadcrumbs([
          ['Відзнаки', 'admin.awards'],
          [$award->title],
        ], true),
      ]);
    }

    public function postUpdate($request, $response) {

      $validation = $this->validator->validate($request, [
        'title' => Validator::notVoid(),
      ]);
  
      if ($validation->failed()) {
        $this->flash->addMessage('message', 'Помилка редагування відзнаки');
        return $response->withRedirect($this->router->pathFor('admin.award.update', [
          'id' => $request->getParam('id'),
        ]));
      }
  
      $award = Award::find($request->getParam('id'));
      $award->update([
        'section_id' => $request->getParam('section_id'),
        'title' => $request->getParam('title'),
        'content' => $request->getParam('content'),
        'is_actual' => $request->getParam('is_actual')  ? true : false,
        'user_id' => $this->auth->user()->id,
      ]);
  
      $this->flash->addMessage('message', 'Відзнаку було відредаговано');
  
      return $response->withRedirect($this->router->pathFor('admin.award.details', [
        'id' => $award->id,
      ]));
    
    }
    
    public function getCreate($request, $response) {
      $sections = Section::where('is_actual', true)->get();
      return $this->view->render($response, 'admin/award/create.twig', [
        'sections' => $sections,
        'breadcrumbs' => Pages::breadcrumbs([
          ['Відзнаки', 'admin.awards'],
          ['Створити'],
        ], true),
      ]);
    }

    public function postCreate($request, $response) {

      $validation = $this->validator->validate($request, [
        'title' => Validator::notVoid(),
      ]);
  
      if ($validation->failed()) {
        $this->flash->addMessage('message', 'Помилка створення відзнаки');
        return $response->withRedirect($this->router->pathFor('admin.award.create'));
      }
        
      Award::create([
        'section_id' => $request->getParam('section_id'),
        'title' => $request->getParam('title'),
        'content' => $request->getParam('content'),
        'is_actual' => $request->getParam('is_actual')  ? true : false,
        'user_id' => $this->auth->user()->id,
      ]);

      $id = Award::max('id');

      $this->flash->addMessage('message', 'Відзнаку було створено');
  
      return $response->withRedirect($this->router->pathFor('admin.award.details', [
        'id' => $id,
      ]));

    }

    public function postUpload($request, $response) {
      $award = Award::find($request->getParam('id'));
  
      $path = Files::getPath([
        $this->abspath, 'public', 'images', 'awards'
      ]);
  
      $files = $request->getUploadedFiles();
      $file = $files['file'];
      $fileName = Files::moveFile($file, $path);
  
      if ($fileName) {
        $award->image = $fileName;
        $award->user_id = $this->auth->user()->id;
        $award->save();
        $this->flash->addMessage('message', 'Зображення було завантажено');   
      } else {
         $this->flash->addMessage('message', 'Помилка завантаження файлу!'); 
      }
      return $response->withRedirect($this->router->pathFor('admin.award.details', [
        'id' => $award->id,    
      ]));
    }

    public function removeFile($request, $response, $args) {
      $file = Files::getPath([
        $this->abspath, 'public', 'images', 'awards', $args['file']
      ]);
  
      if (unlink($file)) {
        $this->flash->addMessage('message', 'Файл було видалено');
        $award = Award::find($args['id']);
        $award->image = null;
        $award->save();
      } else {
        $this->flash->addMessage('message', 'Помилка видалення файлу');
      }
      
      return $response->withRedirect($this->router->pathFor('admin.award.details', [
        'id' => $args['id'], 
      ]));
      
    }
  

}