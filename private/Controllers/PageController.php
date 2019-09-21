<?php
namespace App\Controllers;

use \App\Models\Carousel;
use \App\Models\Information;

class PageController extends Controller {

  public function home($request, $response) {
    return $this->view->render($response, 'guest/home.twig', [
      'activePage' => 'home',
      'carousel' => Carousel::where('is_actual', true)->orderBy('rank')->get(),
      'about' => Information::where('is_actual', true)->where('slug', 'about')->first(),
      'contacts' => Information::where('is_actual', true)->where('slug', 'contacts')->first(),
      'breadcrumbs' => \App\Common\Pages::breadcrumbs()
    ]);
  }

  public function contacts($request, $response) {
    return $this->view->render($response, 'guest/contacts.twig', [
      'activePage' => 'contacts',
      'contacts' => Information::where('is_actual', true)->where('slug', 'contacts')->first(),
      'breadcrumbs' => \App\Common\Pages::breadcrumbs([
        ['Контактна інформаіця'],
      ]),
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