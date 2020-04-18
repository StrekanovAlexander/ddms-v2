<?php 
namespace App\Controllers;

use \App\Common\Files;
use \App\Common\Pages;
use \App\Models\Department;
use \App\Models\Section;
use \App\Models\Subject;
use \App\Models\Task;
use \App\Models\Teacher;
use \Respect\Validation\Validator;

class RemoteController extends Controller {

  public function index($request, $response, $args) {
    $section = Section::find($args['id']);  
    return $this->view->render($response, 'guest/remote/index.twig', [
        'section' => $section,
        'teachers' => Teacher::remote($section->id),
        'activePage' => 'remote',
        'breadcrumbs' => Pages::breadcrumbs([
            ['Дистанційне навчання', 'remote.index'],
            [$section->title],
        ]),
    ]);
  }

  public function tasks($request, $response, $args) {
    $teacher = Teacher::find($args['id']);  
    $section = Department::where('id', $teacher->department_id)->first()->section;
    $tasks = Task::teacherTasks($teacher->id);
    return $this->view->render($response, 'guest/remote/tasks.twig', [
      'teacher' => $teacher,
      'tasks' => Pages::pagination($tasks->toArray(), $request->getParam('page', 1), 12),
      'activePage' => 'remote',
        'breadcrumbs' => Pages::breadcrumbs([
          ['Дистанційне навчання', 'remote.index'],
          [$section->title, 'remote.index', $section->id],
          [$teacher->full_name],
      ]),
    ]);
  }

  public function task($request, $response, $args) {
    $task = Task::find($args['id']);  
    return $this->view->render($response, 'guest/remote/details.twig', [
      'task' => $task,
      'activePage' => 'remote',
      'breadcrumbs' => Pages::breadcrumbs([
        ['Дистанційне навчання'],
        [$task->section->title, 'remote.index', $task->section->id],
        [$task->teacher->full_name, 'remote.tasks', $task->teacher->id],
        ['Завдання: ' . $task->id],
      ]),
    ]);
  }

  public function adminIndex($request, $response, $args) {
    $section = Section::find($args['id']);  
    $tasks = Task::tasks($section->id)->toArray();
    return $this->view->render($response, 'admin/remote/index.twig', [
      'section' => $section,
      'tasks' => Pages::pagination($tasks, $request->getParam('page', 1), 10),
      'breadcrumbs' => Pages::breadcrumbs([
         ['Дістанційне навчання: ' . $section->title],
      ], true),
    ]);
  }

  public function getCreate($request, $response, $args) {
    $section = Section::find($args['id']);  
    return $this->view->render($response, 'admin/remote/create.twig', [
      'section' => $section,
      'subjects' =>  Subject::orderBy('title')->where('section_id', $section->id)->get(),
      'teachers' =>  Teacher::remote($section->id),
      'breadcrumbs' => Pages::breadcrumbs([
        ['Дістанційне навчання: ' . $section->title],
        ['Створити'],
      ], true),
    ]);
  }

  public function postCreate($request, $response) {
    $validation = $this->validator->validate($request, [
      'title' => Validator::notVoid(),
    ]);

    if ($validation->failed()) {
      $this->flash->addMessage('message', 'Помилка створення дістанційного завдання');
      return $response->withRedirect($this->router->pathFor('admin.remote.create', [
        'id' => $request->getParam('sectionId'),
      ]));
    }

    Task::create([
      'section_id' => $request->getParam('section_id'),
      'subject_id' => $request->getParam('subject_id'),
      'teacher_id' => $request->getParam('teacher_id'),
      'grades' => $request->getParam('grades'),
      'title' => $request->getParam('title'),
      'content' => $request->getParam('content'),
      'is_actual' => $request->getParam('is_actual')  ? true : false,
      'user_id' => $this->auth->user()->id,
    ]);

    $taskId = Task::max('id');

    $this->flash->addMessage('message', 'Завдання було створено');

    return $response->withRedirect($this->router->pathFor('admin.remote.details', [
      'id' => $taskId,
    ]));

  }

  public function details($request, $response, $args) {
    $task = Task::find($args['id']);
    return $this->view->render($response, 'admin/remote/details.twig', [
      'task' => $task,
      'breadcrumbs' => Pages::breadcrumbs([
        ['Дистанційне навчання'],
      ], true),
    ]);
  }

  public function getUpdate($request, $response, $args) {
    $task = Task::find($args['id']);

    $images = null;
    if ($task->folder) {
       $path = Files::getPath([
        $this->abspath, 'public', 'images', 'tasks', $task->folder
      ]);
      $images = Files::files($path);
    }

    return $this->view->render($response, 'admin/remote/update.twig', [
      'task' => $task,
      'images' => $images,
      'subjects' =>  Subject::orderBy('title')->where('section_id', $task->section_id)->get(),
      'teachers' =>  Teacher::remote($task->section_id),
      'breadcrumbs' => Pages::breadcrumbs([
        ['Дистанційне навчання: ' . $task->section->title],
        [$task->title, 'admin.remote.details', $task->id],
        ['Редагування'],
      ], true),
    ]);
  }

  public function postUpdate($request, $response) {

    $validation = $this->validator->validate($request, [
      'title' => Validator::notVoid(),
    ]);

    if ($validation->failed()) {
      $this->flash->addMessage('message', 'Помилка редагування завдання');
      return $response->withRedirect($this->router->pathFor('admin.remote.update', [
        'id' => $request->getParam('id'),
      ]));
    }

    $task = Task::find($request->getParam('id'));
    $task->update([
      'section_id' => $request->getParam('section_id'),
      'subject_id' => $request->getParam('subject_id'),
      'teacher_id' => $request->getParam('teacher_id'),
      'grades' => $request->getParam('grades'),
      'title' => $request->getParam('title'),
      'content' => $request->getParam('content'),
      'is_actual' => $request->getParam('is_actual')  ? true : false,
      'user_id' => $this->auth->user()->id,
    ]);

    $this->flash->addMessage('message', 'Завдання було відредаговано');

    return $response->withRedirect($this->router->pathFor('admin.remote.details', [
      'id' => $task->id,
    ]));

  }  

  public function files($request, $response, $args) {
    $task = Task::find($args['id']);
    $images = null;
    if ($task->folder) {
       $path = Files::getPath([
        $this->abspath, 'public', 'images', 'tasks', $task->folder
      ]);
      $images = Files::files($path);
    }
    return $this->view->render($response, 'admin/remote/files.twig', [
      'task' => $task,
      'images' => $images,
      'breadcrumbs' => Pages::breadcrumbs([
        ['Дистанційне навчання: ' . $task->section->title],
        [$task->title, 'admin.remote.details', $task->id],
        ['Файли'],
      ], true),
    ]);
  }

  public function postUpload($request, $response) {
    
    $task = Task::find($request->getParam('id'));
    $folder = $task->folder ? $task->folder : date('Ymd') . '-' . rand(100, 999); 
    
    $path = Files::getPath([
      $this->abspath, 'public', 'images', 'tasks', $folder
    ]);
    
    if (!file_exists($path)) {
      mkdir($path, 0777);
    }
    
    $fileName = null;
    $files = $request->getUploadedFiles();
    foreach ($files['files'] as $file) {
       $fileName = Files::moveFileRandomName($file, $path);
    }

    if ($fileName) {
      if (!$task->folder) {   
        $task->folder = $folder;
      }
      $task->user_id = $this->auth->user()->id;
      $task->save();
      $this->flash->addMessage('message', 'Зображення було завантажено');   
    } else {
       $this->flash->addMessage('message', 'Помилка завантаження файлу!'); 
    }
    return $response->withRedirect($this->router->pathFor('admin.remote.files', [
      'id' => $task->id,    
    ]));

  }

  public function removeFile($request, $response, $args) {

    $task = Task::find($args['id']);

    $file = Files::getPath([
      $this->abspath, 'public', 'images', 'tasks', $task->folder, $args['file']
    ]);

    if (unlink($file)) {
      $this->flash->addMessage('message', 'Файл було видалено');
    } else {
      $this->flash->addMessage('message', 'Помилка видалення файлу');
    }
    
    return $response->withRedirect($this->router->pathFor('admin.remote.files', [
      'id' => $args['id'], 
    ]));
    
  }
  
}