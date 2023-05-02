<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

require __DIR__ . '/../src/config/database.php';
require __DIR__ . '/../src/libs/xssClean.php';
require __DIR__ . '/../src/libs/hashString.php';

require __DIR__ . '/api/v0/middlewares/json_bodyparser.php';

// Config'e ekle
// $app->addErrorMiddleware(false, true, true);


// Tüm User Listesi Getir
$app->get('/users', function (Request $request, Response $response) {
    require __DIR__ . '/api/v0/models/user.php';
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
    require __DIR__ . '/api/v0/models/user.php';
    require __DIR__ . '/api/v0/controllers/user.php';

    $params = (array) $request->getParsedBody();

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

$app->post('/chatgroup/add', function (Request $request, Response $response) {
    require __DIR__ . '/api/v0/models/chat_group.php';
    require __DIR__ . '/api/v0/models/user.php';
    require __DIR__ . '/api/v0/controllers/chat_group.php';
    require __DIR__ . '/api/v0/controllers/user.php';

    $params = (array) $request->getParsedBody();

    $user = new UserController();
    $response_body = $user->getUser($params["created_by"]);

    if ($response_body["item_count"] == 1) {
        $chat_group = new ChatGroupController();
        $response_body = $chat_group->addChatGroup($params);
    }

    $response_json = json_encode($response_body);
    $response->getBody()->write($response_json);

    return $response
        ->withStatus($response_body["http_code"])
        ->withHeader("Content-Type", "application/json");


    // Add to middleware
    // return $response->withHeader('Content-Type', 'application/json');
})->addMiddleware(new JsonBodyParserMiddleware());

// Joinden Devam Edilecek
$app->post('/chatgroup/join', function (Request $request, Response $response) {
    require __DIR__ . '/api/v0/models/chat_group.php';
    require __DIR__ . '/api/v0/models/user.php';
    require __DIR__ . '/api/v0/controllers/chat_group.php';
    require __DIR__ . '/api/v0/controllers/user.php';

    $params = (array) $request->getParsedBody();

    $user = new UserController();
    $response_body = $user->getUser($params["created_by"]);

    if ($response_body["item_count"] == 1) {
        $chat_group = new ChatGroupController();
        $response_body = $chat_group->addChatGroup($params);
    }

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