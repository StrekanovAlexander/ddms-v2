<?php
namespace App\Controllers;

use \App\Common\Pages;
use \App\Models\Audio;

class AudioController extends Controller {

  public function minus($request, $response) {

    $audio = Audio::where('is_actual', true)->get();
    return $this->view->render($response, 'guest/minus.twig', [
      'audio' => $audio,
      'breadcrumbs' => Pages::breadcrumbs([
        ['Мінусові фонограми']
      ]),
    ]);

  }

}