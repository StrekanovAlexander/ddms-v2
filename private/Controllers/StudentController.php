<?php
namespace App\Controllers;

use \App\Common\Files;
use \App\Common\Pages;
use \App\Models\Section;
use \App\Models\Student;
use \Respect\Validation\Validator;

class StudentController extends Controller {

  public function students($reauest, $response, $args) {
    $students = Student::where('slug', $args['id'])->where('is_actual', true)->orderBy('id', 'DESC')->get();
    return $this->view->render($response, 'guest/student/index.twig', [
      'students' => $students,
      'breadcrumbs' => Pages::breadcrumbs([
        ['Наші випускники'],
      ]),
    ]);
  }

  public function student($reauest, $response, $args) {
    $student = Student::where('is_actual', true)->find($args['id']);
    return $this->view->render($response, 'guest/student/details.twig', [
      'student' => $student,
      'breadcrumbs' => Pages::breadcrumbs([
        ['Наші випускники', 'students', $student->slug],
        [$student->full_name],
      ]),
    ]);
  }


  public function index($request, $response) {
    $students = Student::students()->toArray();
    return $this->view->render($response, 'admin/student/index.twig', [
      'students' => Pages::pagination($students, $request->getParam('page', 1), 5),
      'breadcrumbs' => Pages::breadcrumbs([
        ['Випускники'],
      ], true),
    ]);
  }

public function details($request, $response, $args) {
  $student = Student::find($args['id']);
  return $this->view->render($response, 'admin/student/details.twig', [
    'student' => $student,
    'breadcrumbs' => Pages::breadcrumbs([
      ['Випускники', 'admin.students'],
      [$student->full_name],
      ], true),
  ]);
}

public function getUpdate($request, $response, $args) {
  $student = Student::find($args['id']);
  return $this->view->render($response, 'admin/student/update.twig', [
    'student' => $student,
    'sections' => Section::get(),
    'breadcrumbs' => Pages::breadcrumbs([
      ['Випускники', 'admin.students'],
      [$student->full_name, 'admin.student.details', $student->id],
      ['Редагування'],
    ], true),
  ]);
}

  public function postUpdate($request, $response) {

    $validation = $this->validator->validate($request, [
      'full_name' => Validator::notVoid(),
    ]);

    if ($validation->failed()) {
      $this->flash->addMessage('message', 'Помилка редагування випускника');
      return $response->withRedirect($this->router->pathFor('admin.student.update', [
        'id' => $request->getParam('id'),
      ]));
    }

    $student = Student::find($request->getParam('id'));
    $student->update([
      'section_id' => $request->getParam('section_id'),
      'full_name' => $request->getParam('full_name'),
      'content' => $request->getParam('content'),
      'final_year' => $request->getParam('final_year'),
      'slug' => $request->getParam('section_id') == '1' ? 'music' : 'art', 
      'is_actual' => $request->getParam('is_actual') ? true : false,
      'user_id' => $this->auth->user()->id,
    ]);

    $this->flash->addMessage('message', 'Випускника було відредаговано');

    return $response->withRedirect($this->router->pathFor('admin.student.details', [
      'id' => $student->id,
    ]));
  
  }
  
  public function getCreate($request, $response) {
    return $this->view->render($response, 'admin/student/create.twig', [
      'sections' => Section::get(),
      'breadcrumbs' => Pages::breadcrumbs([
        ['Випускники', 'admin.students'],
        ['Створити'],
      ], true),
    ]);
  }

  public function postCreate($request, $response) {

    $validation = $this->validator->validate($request, [
      'full_name' => Validator::notVoid(),
    ]);

    if ($validation->failed()) {
      $this->flash->addMessage('message', 'Помилка створення випускника');
      return $response->withRedirect($this->router->pathFor('admin.student.create'));
    }
      
    Student::create([
      'section_id' => $request->getParam('section_id'),
      'full_name' => $request->getParam('full_name'),
      'content' => $request->getParam('content'),
      'final_year' => $request->getParam('final_year'),
      'slug' => $request->getParam('section_id') == '1' ? 'music' : 'art', 
      'is_actual' => $request->getParam('is_actual') ? true : false,
      'user_id' => $this->auth->user()->id,
    ]);

    $id = Student::max('id');

    $this->flash->addMessage('message', 'Випускника було створено');

    return $response->withRedirect($this->router->pathFor('admin.student.details', [
      'id' => $id,
    ]));

  }

  public function postUpload($request, $response) {
    $student = Student::find($request->getParam('id'));

    $path = Files::getPath([
      $this->abspath, 'public', 'images', 'students'
    ]);

    $files = $request->getUploadedFiles();
    $file = $files['file'];
    $fileName = Files::moveFileRandomName($file, $path);

    if ($fileName) {
      $student->image = $fileName;
      $student->user_id = $this->auth->user()->id;
      $student->save();
      $this->flash->addMessage('message', 'Зображення було завантажено');   
    } else {
       $this->flash->addMessage('message', 'Помилка завантаження файлу!'); 
    }
    return $response->withRedirect($this->router->pathFor('admin.student.details', [
      'id' => $student->id,    
    ]));
  }

public function removeFile($request, $response, $args) {
    $file = Files::getPath([
      $this->abspath, 'public', 'images', 'students', $args['file']
    ]);

    if (unlink($file)) {
      $this->flash->addMessage('message', 'Файл було видалено');
      $student = Student::find($args['id']);
      $student->image = null;
      $student->save();
    } else {
      $this->flash->addMessage('message', 'Помилка видалення файлу');
    }
    
    return $response->withRedirect($this->router->pathFor('admin.student.details', [
      'id' => $args['id'], 
    ]));
    
  }


}