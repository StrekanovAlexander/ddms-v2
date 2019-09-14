<?php
namespace App\Controllers;

use \App\Common\Pages;
use \App\Models\Department;
use \App\Models\Teacher;

class DepartmentController extends Controller {

  public function department($request, $response, $args) {
    $department = Department::where('slug', $args['slug'])->first();
    $teachers = Teacher::where('department_id', $department->id)->where('is_actual', true)->orderBy('rank')->get();
    return $this->view->render($response, 'guest/department.twig', [
      'department' => $department,
      'teachers' => $teachers,
      'breadcrumbs' => Pages::breadcrumbs([
        [$department->title]
      ]),
    ]);
  }

}