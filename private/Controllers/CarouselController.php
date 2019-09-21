<?php
namespace App\Controllers;

use \App\Common\Files;
use \App\Common\Pages;
use \App\Models\Carousel;

class CarouselController extends Controller {

  public function index($request, $response) {
    return $this->view->render($response, 'admin/carousel/index.twig', [
      'carousel' => Carousel::orderBy('rank')->get(),
      'breadcrumbs' => Pages::breadcrumbs([
        ['Карусель']
      ], true),
    ]);
  }

  public function details($request, $response, $args) {
    $carousel = Carousel::find($args['id']);
    return $this->view->render($response, 'admin/carousel/details.twig', [
      'carousel' => $carousel,
      'breadcrumbs' => Pages::breadcrumbs([
        ['Карусель', 'admin.carousel'],
        [$carousel->id],
      ], true),
    ]);
  }

  public function getUpdate($request, $response, $args) {
    $carousel = Carousel::find($args['id']);
    return $this->view->render($response, 'admin/carousel/update.twig', [
      'carousel' => $carousel,
      'breadcrumbs' => Pages::breadcrumbs([
        ['Карусель', 'admin.carousel'],
        [$carousel->id, 'admin.carousel.details', $carousel->id],
        ['Редагувати'],
      ], true),
    ]);
  }

  public function postUpdate($request, $response) {
    $carousel = Carousel::find($request->getParam('id'));
    $carousel->update([
      'title' => $request->getParam('title'),
      'content' => $request->getParam('content'),
      'rank' => $request->getParam('rank'),
      'is_actual' => $request->getParam('is_actual')  ? true : false,
    ]);

    $this->flash->addMessage('message', 'Елемент каруселі було відредаговано');

    return $response->withRedirect($this->router->pathFor('admin.carousel.details', [
      'id' => $carousel->id,
    ]));
  }


  public function postUpload($request, $response) {
    $carousel = Carousel::find($request->getParam('id'));

    $path = Files::getPath([
      $this->abspath, 'public', 'images', 'carousel'
    ]);

    $files = $request->getUploadedFiles();
    $file = $files['file'];
    $fileName = Files::moveFileRandomName($file, $path);

    if ($fileName) {
      $carousel->image = $fileName;
      $carousel->save();
      $this->flash->addMessage('message', 'Зображення було завантажено');   
    } else {
       $this->flash->addMessage('message', 'Помилка завантаження файлу!'); 
    }
    return $response->withRedirect($this->router->pathFor('admin.carousel.details', [
      'id' => $carousel->id,    
    ]));
  }


  public function removeFile($request, $response, $args) {
    $file = Files::getPath([
      $this->abspath, 'public', 'images', 'carousel', $args['file']
    ]);

    if (unlink($file)) {
      $this->flash->addMessage('message', 'Файл було видалено');
      $carousel = Carousel::find($args['id']);
      $carousel->image = null;
      $carousel->save();
    } else {
      $this->flash->addMessage('message', 'Помилка видалення файлу');
    }
    
    return $response->withRedirect($this->router->pathFor('admin.carousel.details', [
      'id' => $args['id'], 
    ]));
    
  }

}