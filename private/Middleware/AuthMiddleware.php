<?php
namespace App\Middleware;

class AuthMiddleware extends Middleware {
  
  public function __invoke($request, $response, $next) {
    if (!$this->container->auth->isAuth()) {
      return $response->withRedirect($this->container->router->pathFor('admin.user.login'));
    }
    $response = $next($request, $response);
    return $response;
  }

}