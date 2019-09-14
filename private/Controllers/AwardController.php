<?php
namespace App\Controllers;

use \App\Common\Pages;

class AwardController extends Controller {

    public function awards($request, $response) {
        $awards = \App\Models\Award::where('is_actual', true)->get()->toArray();
        return $this->view->render($response, 'guest/awards.twig', [
            'awards' => Pages::pagination($awards, $request->getParam('page', 1), 12),
            'activePage' => 'awards',
            'breadcrumbs' => Pages::breadcrumbs([
                ['Відзнаки'],
            ]),
        ]);
    }

}