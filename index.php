<?php

use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Dotenv\Dotenv;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Slim\Exception\HttpNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/vendor/autoload.php';

$dotenv = DotEnv::createImmutable(__DIR__);
$dotenv->load();

require __DIR__ . '/router/router.php';

$app = AppFactory::create();


$errorHandler = function(Request $request, $exception) use ($app){
    $response = $app->getResponseFactory()->createResponse();
    if($exception instanceof HttpNotFoundException){
        return $response->withHeader("Location", "/")->withStatus(302);
    }else{
        return $response->withStatus(500);
    }
};

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($errorHandler);


$twig = Twig::create(__DIR__ . '/views', ['cache' => false, 'debug' => true]);
$app->add(TwigMiddleware::create($app, $twig));

use_router($app);

$app->run();


?>