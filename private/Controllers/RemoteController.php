<?php 
namespace App\Controllers;

use \App\Common\Pages;
use \App\Models\Section;
use \App\Models\Subject;
use \App\Models\Task;
use \App\Models\Teacher;
use \Respect\Validation\Validator;

class RemoteController extends Controller {

  public function index($request, $response, $args) {
    
    $section = Section::find($args['id']);  
    $sectionTitle = $section->title;
    return $this->view->render($response, 'guest/remote/index.twig', [
        'sectionTitle' => $sectionTitle,
    //  'teachers' => Pages::pagination($teachers, $request->getParam('page', 1), 9),
        'activePage' => 'remote',
        'breadcrumbs' => Pages::breadcrumbs([
            ['Дистанційне навчання'],
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
    return $this->view->render($response, 'admin/remote/update.twig', [
      'task' => $task,
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

  
}