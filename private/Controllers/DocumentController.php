<?php
namespace App\Controllers;

use \App\Common\Pages;
use \App\Models\Document;

class DocumentController extends Controller {

  public function documents($request, $response) {

    return $this->view->render($response, 'guest/documents.twig', [
      'documents' => Document::where('is_actual', true)->get(),
      'activePage' => 'documents',
      'breadcrumbs' => Pages::breadcrumbs([
        ['Нормативні документи'],
      ]),
    ]);

  }

}