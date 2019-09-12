<?php 
namespace App\Controllers;

use App\Common\Pages;

class TeacherController extends Controller {

  public function teachers($request, $response) {
    return $this->view->render($response, 'guest/teacher/index.twig', [
      'breadcrumbs' => \App\Common\Pages::breadcrumbs([
        ['Teachers'],
      ]),
    ]);
  }

}