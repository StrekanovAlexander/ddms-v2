<?php
namespace App\Middleware;

class AdminMiddleware extends Middleware {

  public function __invoke($request, $response, $next) {

    if (!$this->container->auth->isAuth()) {
      return $response->withRedirect($this->container->router->pathFor('home'));  
    }
    
    if (!$this->container->auth->user()->is_admin) {
      return $response->withRedirect($this->container->router->pathFor('admin.dashboard'));
    }

    $response = $next($request, $response);
    
    return $response;
    
 }

}