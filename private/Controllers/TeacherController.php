<?php 
namespace App\Controllers;

use \App\Common\Files;
use \App\Common\Pages;
use \App\Models\Department;
use \App\Models\Teacher;
use \Respect\Validation\Validator;

class TeacherController extends Controller {

  public function teachers($request, $response) {
    $teachers = Teacher::where('is_actual', true)->orderBy('rank')->get()->toArray();
    return $this->view->render($response, 'guest/teacher/index.twig', [
      'teachers' => Pages::pagination($teachers, $request->getParam('page', 1), 9),
      'activePage' => 'teachers',
      'breadcrumbs' => Pages::breadcrumbs([
        ['Викладачі'],
      ]),
    ]);
  }

  public function teacher($request, $response, $args) {
    $teacher = Teacher::where('is_actual', true)->find($args['id']);
    return $this->view->render($response, 'guest/teacher/details.twig', [
      'teacher' => $teacher,
      'activePage' => 'teachers',
      'breadcrumbs' => Pages::breadcrumbs([
        ['Викладачі', 'teachers'],
        [$teacher->full_name],
      ]),
    ]);
  }

  public function index($request, $response) {
    $teachers = Teacher::teachers()->toArray();
    return $this->view->render($response, 'admin/teacher/index.twig', [
      'teachers' => Pages::pagination($teachers, $request->getParam('page', 1), 5),
      'breadcrumbs' => Pages::breadcrumbs([
        ['Викладачі'],
      ], true),
    ]);
  }

  public function getUpdate($request, $response, $args) {
    $teacher = Teacher::find($args['id']);
    $departments = Department::get();
    return $this->view->render($response, 'admin/teacher/update.twig', [
      'teacher' => $teacher,
      'departments' => $departments,
      'breadcrumbs' => Pages::breadcrumbs([
        ['Викладачі', 'admin.teachers'],
        ['Редагування: ' . $reacher->full_name ]
      ])
    ]);
    
  }

  public function postUpdate($request, $response) {

    $validation = $this->validator->validate($request, [
      'full_name' => Validator::notVoid(),
      'content' => Validator::notVoid(),
    ]);

    if ($validation->failed()) {
      $this->flash->addMessage('message', 'Помилка редагування викладача');
      return $response->withRedirect($this->router->pathFor('admin.teacher.update', [
        'id' => $request->getParam('id'),
      ]));
    }

    $teacher = Teacher::find($request->getParam('id'));
    $teacher->update([
      'department_id' => $request->getParam('department_id'),
      'full_name' => $request->getParam('full_name'),
      'content' => $request->getParam('content'),
      'rank' => $request->getParam('rank'),
      'phones' => $request->getParam('phones'),
      'is_remote' => $request->getParam('is_remote')  ? true : false,
      'is_actual' => $request->getParam('is_actual')  ? true : false,
      'user_id' => $this->auth->user()->id,
    ]);

    $this->flash->addMessage('message', 'Викладача було відредаговано');

    return $response->withRedirect($this->router->pathFor('admin.teacher.details', [
      'id' => $teacher->id,
    ]));

  }

  public function details($request, $response, $args) {
    $teacher = Teacher::find($args['id']);
    return $this->view->render($response, 'admin/teacher/details.twig', [
      'teacher' => $teacher,
      'breadcrumbs' => Pages::breadcrumbs([
        ['Викладачі', 'admin.teachers'],
        [$teacher->full_name],
      ], true),
    ]);
  }

  public function postUpload($request, $response) {
    $teacher = Teacher::find($request->getParam('id'));

    $path = Files::getPath([
      $this->abspath, 'public', 'images', 'teachers'
    ]);

    $files = $request->getUploadedFiles();
    $file = $files['file'];
    $fileName = Files::moveFile($file, $path);

    if ($fileName) {
      if ($request->getParam('image-preview')) {
        $teacher->image_preview = $fileName;
      } else {
        $teacher->image = $fileName;
      }
      $teacher->user_id = $this->auth->user()->id;   
      $teacher->save();
      $this->flash->addMessage('message', 'Зображення було завантажено');   
    } else {
       $this->flash->addMessage('message', 'Помилка завантаження файлу!'); 
    }
    return $response->withRedirect($this->router->pathFor('admin.teacher.details', [
      'id' => $teacher->id,    
    ]));
  }

  public function removeFile($request, $response, $args) {
    $file = Files::getPath([
      $this->abspath, 'public', 'images', 'teachers', $args['file']
    ]);

    if (unlink($file)) {
      $this->flash->addMessage('message', 'Файл було видалено');
      $teacher = Teacher::find($args['id']);
      $teacher[$args['field']] = null;
      $teacher->save();
    } else {
      $this->flash->addMessage('message', 'Помилка видалення файлу');
    }
    
    return $response->withRedirect($this->router->pathFor('admin.teacher.details', [
      'id' => $args['id'], 
    ]));
    
  }

  public function getCreate($request, $response) {
    $departments = Department::get();
    return $this->view->render($response, 'admin/teacher/create.twig', [
       'departments' => $departments,
      'breadcrumbs' => Pages::breadcrumbs([
        ['Викладачі', 'admin.teachers'],
        ['Створення']
      ])
    ]);
    
  }

  public function postCreate($request, $response) {

    $validation = $this->validator->validate($request, [
      'full_name' => Validator::notVoid(),
      'content' => Validator::notVoid(),
    ]);

    if ($validation->failed()) {
      $this->flash->addMessage('message', 'Помилка створення викладача');
      return $response->withRedirect($this->router->pathFor('admin.teacher.create'));
    }

    Teacher::create([
      'department_id' => $request->getParam('department_id'),
      'full_name' => $request->getParam('full_name'),
      'content' => $request->getParam('content'),
      'rank' => $request->getParam('rank'),
      'phones' => $request->getParam('phones'),
      'is_remote' => $request->getParam('is_remote')  ? true : false,
      'is_actual' => $request->getParam('is_actual')  ? true : false,
      'user_id' => $this->auth->user()->id,
    ]);

    $id = Teacher::max('id');

    $this->flash->addMessage('message', 'Викладача було створено');

    return $response->withRedirect($this->router->pathFor('admin.teacher.details', [
      'id' => $id,
    ]));


  }


}