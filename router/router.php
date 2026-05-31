<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

require __DIR__ . "/../util/dbo.php";

function use_router($app) {

    $app->get('/', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);

        $conn = createDBInstance();

        $result = $conn->query("SELECT * FROM articles WHERE is_featured = 1 AND is_visible = 1 ORDER BY published_date DESC");

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

    $app->get('/articles/{slug}', function (Request $request, Response $response, $args) {
        $view = Twig::fromRequest($request);

        $slug = $args['slug'];

        $article;

        $conn = createDBInstance();

        $stmt = $conn->prepare("SELECT * FROM articles WHERE slug = ?");
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        $result = $stmt->get_result();

        $article_title;
        $article_author;
        $published_date;
        $updated_date = NULL;
        $content;
        $slug;

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $article_title = $row['article_title'];
                $article_author = $row['article_author'];
                $published_date = $row['published_date'];
                $updated_date = $row['updated_date'];
                $content = $row['content'];
                $slug = $row['slug'];
            }
        }

        return $view->render($response, 'article.page.twig', [
            "article_title" => $article_title,
            "article_author" => $article_author,
            "published_date" => date('D, M. d, Y', strtotime($published_date)),
            "updated_date" => $updated_date !== NULL ? date('D, M. d, Y', strtotime($updated_date)) : NULL,
            "content" => $content,
            "slug" => $slug
        ]);
    });

    $app->get('/articles', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);

        $conn = createDBInstance();

        $result = $conn->query("SELECT * FROM articles WHERE is_visible = 1 ORDER BY published_date DESC");

        $articles = [];

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                array_push($articles, $row);
            }
        }

        return $view->render($response, 'all_articles.page.twig', ["articles" => $articles]);
    });

    $app->get('/knox-fire-petition',  function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);

        return $view->render($response, 'petition_fire.page.twig');
    });

    $app->post('/process-knox-fire-poll-vote', function (Request $request, Response $response) {

        $body = $request->getParsedBody();

        try {

            $fields = ['is_resident', 'is_of_age', 'has_already_voted', 'is_yes_vote'];

            foreach($fields as $field){
                if(!isset($body[$field])){
                    throw new Exception('MISSING_FIELD', 422);
                }
            }

            foreach($fields as $field){
                if(in_array($body[$field], ['yes', 'no'])){
                    throw new Exception('INVALID VALUES', 422);
                }
            }

            function genBi($val){
                return $val === "yes" ? 1 : 0;
            }

            $is_resident = genBi($body['is_resident']);
            $is_of_age = genBi($body['is_of_age']);
            $has_already_voted = genBi($body['has_already_voted']);
            $is_yes_vote = genBi($body['is_yes_vote']);
            $ip = $_SERVER['REMOTE_ADDR'];

            $conn = createDBInstance();

            $stmt = $conn->prepare("INSERT INTO knox_fire_poll_votes(is_resident, is_of_age, has_already_voted, is_yes_vote, ip) VALUES (?,?,?,?,?)");
            $stmt->bind_param("iiiis", $is_resident, $is_of_age, $has_already_voted, $is_yes_vote, $ip);
            $stmt->execute();
            $stmt->close();

            $result = $conn->query("SELECT count(id) AS yescount FROM knox_fire_poll_votes WHERE is_yes_vote = 1");
            $row = $result->fetch_assoc();
            $yes_count = $row['yescount'];

            $result = $conn->query("SELECT count(id) AS nocounts FROM knox_fire_poll_votes WHERE is_yes_vote = 0");
            $row = $result->fetch_assoc();
            $no_count = $row['nocounts'];


            $responseData = json_encode(["message" => "created", "votedYes" => $yes_count, "votedno" => $no_count]);
            $response->getBody()->write($responseData);
            return $response->withHeader('Content-Type','application/json')->withStatus(201);

        }
        catch(Exception $e){
            $payload = json_encode(["error_message" => $e->getMessage()]);
            $code = 500;
            if($e->getCode > 400){
                $code = $e->getCode();
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type','application/json')->withStatus($code);
        }

    });

    

   

}

?>