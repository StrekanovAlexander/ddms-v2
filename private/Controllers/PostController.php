<?php
namespace App\Controllers;

use \App\Common\Files;
use \App\Common\Pages;
use \App\Models\Post;
use \App\Models\Section;
use \Respect\Validation\Validator;

class PostController extends Controller {

  private function slug($section_id) {
    return $section_id === '1' ? 'music-contest' : 'art-contest'; 
  }

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

  public function postUpdate($request, $response) {

    $validation = $this->validator->validate($request, [
      'title' => Validator::notVoid(),
    ]);

    if ($validation->failed()) {
      $this->flash->addMessage('message', 'Помилка редагування події');
      return $response->withRedirect($this->router->pathFor('admin.post.update', [
        'id' => $request->getParam('id'),
      ]));
    }

    $slug =  $request->getParam('is_contest') ? $this->slug($request->getParam('section_id')) : null; 
    
    $post = post::find($request->getParam('id'));
    $post->update([
      'section_id' => $request->getParam('section_id'),
      'title' => $request->getParam('title'),
      'intro' => $request->getParam('intro'),
      'content' => $request->getParam('content'),
      'tags' => $request->getParam('tags'),
      'is_actual' => $request->getParam('is_actual')  ? true : false,
      'is_contest' => $request->getParam('is_contest')  ? true : false,
      'slug' => $slug,
      'user_id' => $this->auth->user()->id,
    ]);

    $this->flash->addMessage('message', 'Подію було відредаговано');

    return $response->withRedirect($this->router->pathFor('admin.post.details', [
      'id' => $post->id,
    ]));
  
  }

  public function getCreate($request, $response) {
    return $this->view->render($response, 'admin/post/create.twig', [
      'sections' => Section::get(),
      'breadcrumbs' => Pages::breadcrumbs([
        ['Події', 'admin.posts'],
        ['Створити'],
      ], true),
    ]);
  }

  public function postCreate($request, $response) {
    $validation = $this->validator->validate($request, [
      'title' => Validator::notVoid(),
    ]);

    if ($validation->failed()) {
      $this->flash->addMessage('message', 'Помилка створення події');
      return $response->withRedirect($this->router->pathFor('admin.post.create', [
        'id' => $request->getParam('id'),
      ]));
    }

    $slug =  $request->getParam('is_contest') ? $this->slug($request->getParam('section_id')) : null; 
    
    Post::create([
      'section_id' => $request->getParam('section_id'),
      'title' => $request->getParam('title'),
      'intro' => $request->getParam('intro'),
      'content' => $request->getParam('content'),
      'tags' => $request->getParam('tags'),
      'is_actual' => $request->getParam('is_actual')  ? true : false,
      'is_contest' => $request->getParam('is_contest')  ? true : false,
      'slug' => $slug,
      'user_id' => $this->auth->user()->id,
    ]);

    $id = Post::max('id');

    $this->flash->addMessage('message', 'Подію було створено');

    return $response->withRedirect($this->router->pathFor('admin.post.details', [
      'id' => $id,
    ]));
    
  }

  public function files($request, $response, $args) {
    $post = Post::find($args['id']);
    $images = null;
    if ($post->folder) {
      $path = Files::getPath([
        $this->abspath, 'public', 'images', 'galleries', $post->folder
      ]);
      $images = Files::files($path);
    }
    return $this->view->render($response, 'admin/post/files.twig', [
      'post' => $post,
      'images' => $images,
      'breadcrumbs' => Pages::breadcrumbs([
        ['Події', 'admin.posts'],
        [$post->id, 'admin.post.details', $post->id],
        ['Файли'],
      ], true),
    ]);
  }

  public function postUpload($request, $response) {
    
    $post = Post::find($request->getParam('id'));
    $folder = $post->folder ? $post->folder : date('Ymd') . '-' . rand(100, 999); 
    
    $path = Files::getPath([
      $this->abspath, 'public', 'images', 'galleries', $folder
    ]);
    
    if (!file_exists($path)) {
      mkdir($path, 0777);
    }
    
    $files = $request->getUploadedFiles();
    $file = $files['file'];
    $fileName = Files::moveFileRandomName($file, $path);

    if ($fileName) {
      if (!$post->folder) {   
        $post->folder = $folder;
      }
      if ($request->getParam('image-main')) {
        $post->image = $fileName;
      } 
      $post->user_id = $this->auth->user()->id;
      $post->save();
      $this->flash->addMessage('message', 'Зображення було завантажено');   
    } else {
       $this->flash->addMessage('message', 'Помилка завантаження файлу!'); 
    }
    return $response->withRedirect($this->router->pathFor('admin.post.files', [
      'id' => $post->id,    
    ]));
  }

  public function removeFile($request, $response, $args) {

    $post = Post::find($args['id']);

    $file = Files::getPath([
      $this->abspath, 'public', 'images', 'galleries', $post->folder, $args['file']
    ]);

    if (unlink($file)) {
      if ($args['main'] == 1) {
        $post->image = null;
        $post->save();
      }
      $this->flash->addMessage('message', 'Файл було видалено');
    } else {
      $this->flash->addMessage('message', 'Помилка видалення файлу');
    }
    
    return $response->withRedirect($this->router->pathFor('admin.post.files', [
      'id' => $args['id'], 
    ]));
    
  }


}