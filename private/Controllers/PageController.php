<?php
namespace App\Controllers;

class PageController extends Controller {

  public function home($request, $response) {
    return $this->view->render($response, 'guest/home.twig', [
      'breadcrumbs' => \App\Common\Pages::breadcrumbs()
    ]);
  }

  public function dashboard($request, $response) {
    return $this->view->render($response, 'admin/dashboard.twig', [
      'breadcrumbs' => \App\Common\Pages::breadcrumbs([
        ['Панель керування']
      ], true)
    ]);
  }

}