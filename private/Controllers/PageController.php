<?php
namespace App\Controllers;

class PageController extends Controller {

  public function home($request, $response) {
    return $this->view->render($response, 'guest/home.twig', [
      'breadcrumbs' => \App\Common\Pages::breadcrumbs()
    ]);
  }

}