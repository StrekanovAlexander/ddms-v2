<?php
namespace App\Middleware;

class UserMiddleware extends Middleware {

  public function __invoke($request, $response, $next) {
    if ($this->container->auth->isAuth()) {
      return $response->withRedirect($this->container->router->pathFor('admin.dashboard'));
    }
    $response = $next($request, $response);
    return $response;
 }

}