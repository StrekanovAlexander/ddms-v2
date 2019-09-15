<?php
namespace App\Controllers;

use \App\Common\Pages;
use \App\Models\Student;

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

}