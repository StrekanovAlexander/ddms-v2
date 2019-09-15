<?php
namespace App\Controllers;

use \App\Common\Files;
use \App\Common\Pages;
use \App\Models\Post;

class PostController extends Controller {

  public function posts($request, $response) {

    $posts = Post::where('is_actual', true)->orderBy('id', 'DESC')->get()->toArray();

    return $this->view->render($response, 'guest/post/index.twig', [
      'posts' => Pages::pagination($posts, $request->getParam('page', 1), 9),
      'activePage' => 'posts',
      'breadcrumbs' => Pages::breadcrumbs([
        ['Події']
      ]),
    ]);

  }

  public function post($request, $response, $args) {

    $post = Post::find($args['id']);

    $path = Files::getPath([
      $this->abspath, 'public', 'images', 'galleries', $post->folder
    ]);  

    $images = Files::files($path);

    return $this->view->render($response, 'guest/post/details.twig', [
      'post' => $post,
      'images' => $images,
      'activePage' => 'posts',
      'breadcrumbs' => Pages::breadcrumbs([
        ['Події', 'posts'],
        [$post->title],
      ]),
    ]);

  }

}