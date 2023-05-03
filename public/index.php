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

$app->patch('/users/online/{user_id}', function (Request $request, Response $response) {
    require __DIR__ . '/api/v0/models/user.php';
    require __DIR__ . '/api/v0/controllers/user.php';

    $user_id = $request->getAttribute("user_id");
    $params = (array) $request->getParsedBody();
    $params["user_id"] = $user_id;

    $user = new UserController();
    $response_body = $user->updateUserAvailability($params);
    $response_json = json_encode($response_body);

    $response->getBody()->write($response_json);

    return $response
        ->withStatus($response_body["http_code"])
        ->withHeader("Content-Type", "application/json");


    // Add to middleware
    // return $response->withHeader('Content-Type', 'application/json');
})->addMiddleware(new JsonBodyParserMiddleware());

$app->post('/chatgroup/create', function (Request $request, Response $response) {
    require __DIR__ . '/api/v0/models/chat_group.php';
    require __DIR__ . '/api/v0/models/user.php';
    require __DIR__ . '/api/v0/controllers/chat_group.php';
    require __DIR__ . '/api/v0/controllers/user.php';

    $params = (array) $request->getParsedBody();

    $user = new UserController();
    $response_body = $user->isAvailable($params["created_by"]);

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
    require __DIR__ . '/api/v0/models/chat_group_member.php';
    require __DIR__ . '/api/v0/models/chat_group.php';
    require __DIR__ . '/api/v0/models/user.php';
    require __DIR__ . '/api/v0/controllers/chat_group_member.php';
    require __DIR__ . '/api/v0/controllers/chat_group.php';
    require __DIR__ . '/api/v0/controllers/user.php';

    $params = (array) $request->getParsedBody();

    $user = new UserController();
    $response_body = $user->isAvailable($params["user_id"]);

    if ($response_body["item_count"] == 1) {
        $chat_group = new ChatGroupController();
        $response_body = $chat_group->getChatGroup($params["chat_group_id"]);

        if ($response_body["item_count"] == 1) {
            $chat_group_member = new ChatGroupMemberController();
            $response_body = $chat_group_member->addGroupMember($params);
        }
    }

    $response_json = json_encode($response_body);
    $response->getBody()->write($response_json);

    return $response
        ->withStatus($response_body["http_code"])
        ->withHeader("Content-Type", "application/json");


    // Add to middleware
    // return $response->withHeader('Content-Type', 'application/json');
})->addMiddleware(new JsonBodyParserMiddleware());

$app->post('/message/send', function (Request $request, Response $response) {
    require __DIR__ . '/api/v0/models/chat_group_member.php';
    require __DIR__ . '/api/v0/models/chat_group.php';
    require __DIR__ . '/api/v0/models/message.php';
    require __DIR__ . '/api/v0/models/user.php';
    require __DIR__ . '/api/v0/controllers/chat_group_member.php';
    require __DIR__ . '/api/v0/controllers/chat_group.php';
    require __DIR__ . '/api/v0/controllers/message.php';
    require __DIR__ . '/api/v0/controllers/user.php';

    $params = (array) $request->getParsedBody();

    $chat_group = new ChatGroupController();
    $response_body = $chat_group->getChatGroup($params["chat_group_id"]);

    if ($response_body["item_count"] == 1) {
        $chat_gmember = new ChatGroupMemberController();
        $response_body = $chat_gmember->getGroupMember($params["chat_gmember_id"]);

        if ($response_body["body"]->CHAT_GROUP_ID == $params["chat_group_id"]) {
            $user = new UserController();
            $response_body = $user->isAvailable($response_body["body"]->USER_ID);

            if ($response_body["item_count"] == 1) {
                $message = new MessageController();
                $response_body = $message->sendMessage($params);
            }
        } else {
            $response_body = array(
                "body" => null,
                "error" => "Error! Not Matched",
                "http_code" => 500
            );
        }
    }

    $response_json = json_encode($response_body);
    $response->getBody()->write($response_json);

    return $response
        ->withStatus($response_body["http_code"])
        ->withHeader("Content-Type", "application/json");


    // Add to middleware
    // return $response->withHeader('Content-Type', 'application/json');
})->addMiddleware(new JsonBodyParserMiddleware());

$app->get('/messages/{chat_group_id}/user/{user_id}', function (Request $request, Response $response) {
    require __DIR__ . '/api/v0/models/message.php';
    require __DIR__ . '/api/v0/controllers/message.php';
    require __DIR__ . '/api/v0/models/chat_group_member.php';
    require __DIR__ . '/api/v0/controllers/chat_group_member.php';

    $user_id = $request->getAttribute("user_id");
    $chat_group_id = $request->getAttribute("chat_group_id");

    $chat_group_member = new ChatGroupMemberController();
    $response_body = $chat_group_member->getGroupMemberByUserId($user_id, $chat_group_id);

    if ($response_body["item_count"] == 1) {
        $message = new MessageController();
        $response_body = $message->getMessagesByGroupId($chat_group_id);
    }

    $response_json = json_encode($response_body);
    $response->getBody()->write($response_json);

    return $response
        ->withStatus($response_body["http_code"])
        ->withHeader("Content-Type", "application/json");


    // Add to middleware
    // return $response->withHeader('Content-Type', 'application/json');
});

// Router User Routes
require "../src/routes/user.php";


$app->run();