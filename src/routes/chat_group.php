<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/chatgroup/create', function (Request $request, Response $response) {
    $params = (array) $request->getParsedBody();

    $user = new UserController();
    $response_body = $user->isAvailable($params["created_by"]);

    if ($response_body["item_count"] == 1) {
        $chat_group = new ChatGroupController();
        $response_body = $chat_group->addChatGroup($params);

        if($response_body["body"] > 0) {
            $params = array( 
                "user_id" => $params["created_by"], 
                "chat_group_id" => $response_body["body"],
                "is_blocked" => 0,
                "is_admin" => 1
            );
            $chat_group_member = new ChatGroupMemberController();
            $response_body = $chat_group_member->addGroupMember($params);
        }
    }

    $response_json = json_encode($response_body);
    $response->getBody()->write($response_json);

    return $response
        ->withStatus($response_body["http_code"])
        ->withHeader("Content-Type", "application/json");

})->addMiddleware(new JsonBodyParserMiddleware());

$app->post('/chatgroup/join', function (Request $request, Response $response) {
    $params = (array) $request->getParsedBody();

    $user = new UserController();
    $response_body = $user->isAvailable($params["user_id"]);

    if ($response_body["item_count"] == 1) {
        $chat_group = new ChatGroupController();
        $response_body = $chat_group->getChatGroup($params["chat_group_id"]);

        if ($response_body["item_count"] == 1) {
            $params["is_blocked"] = 0;
            $params["is_admin"] = 0;
            $chat_group_member = new ChatGroupMemberController();
            $response_body = $chat_group_member->addGroupMember($params);
        }
    }

    $response_json = json_encode($response_body);
    $response->getBody()->write($response_json);

    return $response
        ->withStatus($response_body["http_code"])
        ->withHeader("Content-Type", "application/json");

})->addMiddleware(new JsonBodyParserMiddleware());
