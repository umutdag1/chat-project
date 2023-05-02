<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

require __DIR__ .'/api/v0/middlewares/json_bodyparser.php';


// Config'e ekle
// $app->addErrorMiddleware(false, true, true);


// Tüm User Listesi Getir
$app->get('/users', function (Request $request, Response $response) {
    require __DIR__ . '/api/v0/controllers/user.php';

    $user = new UserController();
    $response_body = $user->getUsers();
    $response_json = json_encode($response_body);

    $response->getBody()->write($response_json);
    
    return $response
        ->withStatus($response_body["http_code"])
        ->withHeader("Content-Type", "application/json");
        

    // Add to middleware
    // return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/users/add', function (Request $request, Response $response) {
    require __DIR__ . '/api/v0/controllers/user.php';
    $params = (array)$request->getParsedBody();

    $user = new UserController();
    $response_body = $user->addUser($params);
    $response_json = json_encode($response_body);

    $response->getBody()->write($response_json);
    
    return $response
        ->withStatus($response_body["http_code"])
        ->withHeader("Content-Type", "application/json");
        

    // Add to middleware
    // return $response->withHeader('Content-Type', 'application/json');
})->addMiddleware(new JsonBodyParserMiddleware());

// Router User Routes
require "../src/routes/user.php";


$app->run();