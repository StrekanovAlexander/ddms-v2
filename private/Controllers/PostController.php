<?php
namespace App\Controllers;

use \App\Common\Files;
use \App\Common\Pages;
use \App\Models\Post;
use \App\Models\Section;

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

  public function processes($request, $response) {

    $posts = Post::where('is_actual', true)->where('section_id', 2)->orderBy('id', 'DESC')->get()->toArray();

    return $this->view->render($response, 'guest/post/processes.twig', [
      'posts' => Pages::pagination($posts, $request->getParam('page', 1), 9),
      'breadcrumbs' => Pages::breadcrumbs([
        ['Художній відділ'],
        ['Учбовий процесс']
      ]),
    ]);

  }

  public function process($request, $response, $args) {

    $post = Post::find($args['id']);

    $path = Files::getPath([
      $this->abspath, 'public', 'images', 'galleries', $post->folder
    ]);  

    $images = Files::files($path);

    return $this->view->render($response, 'guest/post/details.twig', [
      'post' => $post,
      'images' => $images,
      'breadcrumbs' => Pages::breadcrumbs([
        ['Художній відділ'],
        ['Учбовий процес', 'processes'],
        [$post->title],
      ]),
    ]);

  }

  public function contests($request, $response, $args) {
    $section = Post::where('is_actual', true)->where('slug', $args['id'])->first()->section->title; 

    $posts = Post::where('is_actual', true)->where('slug', $args['id'])->orderBy('id', 'DESC')->get()->toArray();

    return $this->view->render($response, 'guest/post/contests.twig', [
      'posts' => Pages::pagination($posts, $request->getParam('page', 1), 6),
      'slug' => $args['id'],
      'section' => $section, 
      'breadcrumbs' => Pages::breadcrumbs([
        ['Конкурси: ' . $section],
      ]),
    ]);

  }

  public function contest($request, $response, $args) {

    $post = Post::find($args['id']);

    $path = Files::getPath([
      $this->abspath, 'public', 'images', 'galleries', $post->folder
    ]);  

    $images = Files::files($path);

    return $this->view->render($response, 'guest/post/details.twig', [
      'post' => $post,
      'images' => $images,
      'breadcrumbs' => Pages::breadcrumbs([
        ['Конкурси', 'contests', $post->slug],
        [$post->title],
      ]),
    ]);

  }

  public function foundation($request, $response) {
    return $this->view->render($response, 'guest/post/empty.twig', [
      'breadcrumbs' => Pages::breadcrumbs([
        ['Золотий фонд'],
      ]),
    ]);
  }

  public function index($request, $response) {
    $posts = Post::posts()->toArray();
    return $this->view->render($response, 'admin/post/index.twig', [
      'posts' => Pages::pagination($posts, $request->getParam('page', 1), 5),
      'breadcrumbs' => Pages::breadcrumbs([
        ['Події'],
      ], true),
    ]);
    
  }

  public function details($request, $response, $args) {
    $post = Post::find($args['id']);
    return $this->view->render($response, 'admin/post/details.twig', [
      'post' => $post,
      'breadcrumbs' => Pages::breadcrumbs([
        ['Події', 'admin.posts'],
        [$post->id],
      ], true),
    ]);
  }

  public function getUpdate($request, $response, $args) {
    $post = Post::find($args['id']);
    return $this->view->render($response, 'admin/post/update.twig', [
      'post' => $post,
      'sections' => Section::get(),
      'breadcrumbs' => Pages::breadcrumbs([
        ['Події', 'admin.posts'],
        [$post->id, 'admin.post.details', $post->id],
        ['Редагування'],
      ], true),
    ]);

  }


}