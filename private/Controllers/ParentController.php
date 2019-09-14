<?php
namespace App\Controllers;

use \App\Common\Pages;
use \App\Models\Account;

class ParentController extends Controller {

    public function parents($request, $response) {

        return $this->view->render($response, 'guest/parents.twig', [
            'accounts' => Account::where('is_actual', true)->get(),
            'activePage' => 'parents',
            'breadcrumbs' => Pages::breadcrumbs([
                ['Батькам']
            ]),
        ]);

    }

}