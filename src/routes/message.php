<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/message/send', function (Request $request, Response $response) {
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

})->addMiddleware(new JsonBodyParserMiddleware());

$app->get('/messages/{chat_group_id}/user/{user_id}', function (Request $request, Response $response) {
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

})->addMiddleware(new JsonBodyParserMiddleware());