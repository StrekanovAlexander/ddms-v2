<?php 
namespace App\Controllers;

use App\Common\Pages;
use App\Models\Teacher;

class TeacherController extends Controller {

  public function teachers($request, $response) {
    $teachers = Teacher::where('is_actual', true)->orderBy('rank')->get()->toArray();
    return $this->view->render($response, 'guest/teacher/index.twig', [
      'teachers' => Pages::pagination($teachers, $request->getParam('page', 1), 9),
      'activePage' => 'teachers',
      'breadcrumbs' => Pages::breadcrumbs([
        ['Викладачи'],
      ]),
    ]);
  }

  public function teacher($request, $response, $args) {
    $teacher = Teacher::where('is_actual', true)->find($args['id']);
    return $this->view->render($response, 'guest/teacher/details.twig', [
      'teacher' => $teacher,
      'activePage' => 'teachers',
      'breadcrumbs' => Pages::breadcrumbs([
        ['Викладачи', 'teachers'],
        [$teacher->full_name],
      ]),
    ]);
  }

  public function index($request, $response) {
    $teachers = Teacher::teachers()->toArray();
    return $this->view->render($response, 'admin/teacher/index.twig', [
      'teachers' => Pages::pagination($teachers, $request->getParam('page', 1), 5),
      'breadcrumbs' => Pages::breadcrumbs([
        ['Викладачи'],
      ], true),
    ]);
  }

  public function getUpdate() {
    
  }

  public function postUpdate() {

  }

  public function details($request, $response, $args) {
    $teacher = Teacher::find($args['id']);
    return $this->view->render($response, 'admin/teacher/details.twig', [
      'teacher' => $teacher,
      'breadcrumbs' => Pages::breadcrumbs([
        ['Викладачи', 'admin.teachers'],
        [$teacher->full_name],
      ], true),
    ]);
  }

}