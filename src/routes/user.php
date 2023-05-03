<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/users', function (Request $request, Response $response) {
    $user = new UserController();
    $response_body = $user->getUsers();
    $response_json = json_encode($response_body);

    $response->getBody()->write($response_json);

    return $response
        ->withStatus($response_body["http_code"])
        ->withHeader("Content-Type", "application/json");

});

$app->post('/users/add', function (Request $request, Response $response) {
    $params = (array) $request->getParsedBody();

    $user = new UserController();
    $response_body = $user->addUser($params);
    $response_json = json_encode($response_body);

    $response->getBody()->write($response_json);

    return $response
        ->withStatus($response_body["http_code"])
        ->withHeader("Content-Type", "application/json");

})->addMiddleware(new JsonBodyParserMiddleware());


$app->patch('/users/online/{user_id}', function (Request $request, Response $response) {
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

})->addMiddleware(new JsonBodyParserMiddleware());
