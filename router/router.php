<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

function use_router($app) {

    $app->get('/', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'home.page.twig');
    });

    $app->get('/problems', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'problems.page.twig');
    });

    $app->get('/solutions', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'solutions.page.twig');
    });

}

?>