<?php
namespace App\Controllers;

use \App\Models\Subject;
use \App\Common\Pages;
use \App\Models\Section;
use \Respect\Validation\Validator;

class SubjectController extends Controller {
    public function index($request, $response) {
        $subjects = Subject::orderBy('title')->get();
        return $this->view->render($response, 'admin/subject/index.twig', [
            'subjects' => $subjects,
            'breadcrumbs' => Pages::breadcrumbs([
                ['Предмети'],
            ], true),
        ]);
    }

    public function details($request, $response, $args) {
        $subject = Subject::find($args['id']);
        return $this->view->render($response, 'admin/subject/details.twig', [
          'subject' => $subject,
          'breadcrumbs' => Pages::breadcrumbs([
            ['Предмети', 'admin.subjects'],
            [$subject->title],
          ], true),
        ]);
    }

    public function getCreate($request, $response) {
      $sections = Section::orderBy('title')->get();
      return $this->view->render($response, 'admin/subject/create.twig', [
        'sections' => $sections,
        'breadcrumbs' => Pages::breadcrumbs([
          ['Предмети', 'admin.subjects'],
          ['Створити'],
        ], true),
      ]);
    }

    public function postCreate($request, $response) {
      $validation = $this->validator->validate($request, [
        'title' => Validator::notVoid(),
      ]);

      if ($validation->failed()) {
        $this->flash->addMessage('message', 'Помилка створення предмету');
        return $response->withRedirect($this->router->pathFor('admin.subject.create'));
      }
        
      Subject::create([
        'title' => $request->getParam('title'),
        'section_id' => $request->getParam('section_id'),
        'is_actual' => $request->getParam('is_actual')  ? true : false,
      ]);

      $id = Subject::max('id');

      $this->flash->addMessage('message', 'Предмет було створено');
      return $response->withRedirect($this->router->pathFor('admin.subject.details', [
        'id' => $id,
      ]));
    }

    public function getUpdate($request, $response, $args) {
      $subject = Subject::find($args['id']);
      $sections = Section::get();
      return $this->view->render($response, 'admin/subject/update.twig', [
        'subject' => $subject,
        'sections' => $sections,
        'breadcrumbs' => Pages::breadcrumbs([
          ['Предмети', 'admin.subjects'],
          [$subject->title],
        ], true),
      ]);
    }

    public function postUpdate($request, $response) {
      $validation = $this->validator->validate($request, [
        'title' => Validator::notVoid(),
      ]);
  
      if ($validation->failed()) {
        $this->flash->addMessage('message', 'Помилка редагування предмету');
        return $response->withRedirect($this->router->pathFor('admin.subject.update', [
          'id' => $request->getParam('id'),
        ]));
      }
  
      $subject = Subject::find($request->getParam('id'));
      $subject->update([
        'title' => $request->getParam('title'),
        'section_id' => $request->getParam('section_id'),
        'is_actual' => $request->getParam('is_actual')  ? true : false,
      ]);
  
      $this->flash->addMessage('message', 'Предмет було відредаговано');
  
      return $response->withRedirect($this->router->pathFor('admin.subject.details', [
        'id' => $subject->id,
      ]));

    }




    // public function index($request, $response) {
    //     $awards = Award::awards()->toArray();
    //     return $this->view->render($response, 'admin/award/index.twig', [
    //         'awards' => Pages::pagination($awards, $request->getParam('page', 1), 5),
    //         'breadcrumbs' => Pages::breadcrumbs([
    //            ['Відзнаки'],
    //         ], true),
    //     ]);

    // }

    

    // public function getUpdate($request, $response, $args) {
    //   $award = Award::find($args['id']);
    //   $sections = Section::where('is_actual', true)->get();
    //   return $this->view->render($response, 'admin/award/update.twig', [
    //     'award' => $award,
    //     'sections' => $sections,
    //     'breadcrumbs' => Pages::breadcrumbs([
    //       ['Відзнаки', 'admin.awards'],
    //       [$award->title],
    //     ], true),
    //   ]);
    // }

    // public function postUpdate($request, $response) {

    //   $validation = $this->validator->validate($request, [
    //     'title' => Validator::notVoid(),
    //   ]);
  
    //   if ($validation->failed()) {
    //     $this->flash->addMessage('message', 'Помилка редагування відзнаки');
    //     return $response->withRedirect($this->router->pathFor('admin.award.update', [
    //       'id' => $request->getParam('id'),
    //     ]));
    //   }
  
    //   $award = Award::find($request->getParam('id'));
    //   $award->update([
    //     'section_id' => $request->getParam('section_id'),
    //     'title' => $request->getParam('title'),
    //     'content' => $request->getParam('content'),
    //     'is_actual' => $request->getParam('is_actual')  ? true : false,
    //     'user_id' => $this->auth->user()->id,
    //   ]);
  
    //   $this->flash->addMessage('message', 'Відзнаку було відредаговано');
  
    //   return $response->withRedirect($this->router->pathFor('admin.award.details', [
    //     'id' => $award->id,
    //   ]));
    
    // }
    
    

    

    // public function postUpload($request, $response) {
    //   $award = Award::find($request->getParam('id'));
  
    //   $path = Files::getPath([
    //     $this->abspath, 'public', 'images', 'awards'
    //   ]);
  
    //   $files = $request->getUploadedFiles();
    //   $file = $files['file'];
    //   $fileName = Files::moveFileRandomName($file, $path);
  
    //   if ($fileName) {
    //     $award->image = $fileName;
    //     $award->user_id = $this->auth->user()->id;
    //     $award->save();
    //     $this->flash->addMessage('message', 'Зображення було завантажено');   
    //   } else {
    //      $this->flash->addMessage('message', 'Помилка завантаження файлу!'); 
    //   }
    //   return $response->withRedirect($this->router->pathFor('admin.award.details', [
    //     'id' => $award->id,    
    //   ]));
    // }

    // public function removeFile($request, $response, $args) {
    //   $file = Files::getPath([
    //     $this->abspath, 'public', 'images', 'awards', $args['file']
    //   ]);
  
    //   if (unlink($file)) {
    //     $this->flash->addMessage('message', 'Файл було видалено');
    //     $award = Award::find($args['id']);
    //     $award->image = null;
    //     $award->save();
    //   } else {
    //     $this->flash->addMessage('message', 'Помилка видалення файлу');
    //   }
      
    //   return $response->withRedirect($this->router->pathFor('admin.award.details', [
    //     'id' => $args['id'], 
    //   ]));
      
    // }
  

}