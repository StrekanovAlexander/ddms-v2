<?php
namespace App\Controllers;

use \App\Models\Carousel;
use \App\Models\Information;

class PageController extends Controller {

  public function home($request, $response) {
    return $this->view->render($response, 'guest/home.twig', [
      'activePage' => 'home',
      'carousel' => Carousel::where('is_actual', true)->get(),
      'about' => Information::where('is_actual', true)->where('slug', 'about')->first(),
      'contacts' => Information::where('is_actual', true)->where('slug', 'contacts')->first(),
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