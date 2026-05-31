<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

require __DIR__ . "/util/dbo.php";

function use_router($app) {

    $app->get('/', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);

        $conn = createDBInstance();

        $result = $conn->query("SELECT * FROM articles WHERE is_featured = 1 ORDER BY published_date DESC");

        $featured_articles = [];

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($featured_articles, $row);
            }
        }

        return $view->render($response, 'home.page.twig', ["featured_articles" => $featured_articles]);
    });

    $app->get('/problems', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'problems.page.twig');
    });

    $app->get('/solutions', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'solutions.page.twig');
    });

    $app->get('/take-action', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'take_action.page.twig');
    });

    $app->get('/about-hayden-bradfield', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'about_hayden_bradfield.page.twig');
    });

}

?>