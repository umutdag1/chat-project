<?php
require __DIR__ . '/../models/user.php';

class UserController
{
    public function getUsers()
    {
        $user_model = new UserModel();
        $model_response = $user_model->getUsers();
        $result = array();

        if (isset($model_response["error"])) {
            $result["body"] = null;
            $result["error"] = $model_response["error"];
            $result["http_code"] = 500;
        } else {
            if (isset($model_response["data"])) {
                $stmt = $model_response["data"];
                $result["item_count"] = $stmt->rowCount();
                $result["error"] = null;
                $result["http_code"] = 200;
                
                if($result["item_count"] > 0) {
                    $result["body"] = $stmt->fetchAll(PDO::FETCH_OBJ);
                } else {
                    $result["body"] = array();
                }
            }
        }

        return $result;
    }
    public function getUser($params)
    {
        $user_model = new UserModel();
        $model_response = $user_model->getUsers();
        $result = array();

        if (isset($model_response["error"])) {
            $result["body"] = null;
            $result["error"] = $model_response["error"];
            $result["http_code"] = 500;
        } else {
            if (isset($model_response["data"])) {
                $stmt = $model_response["data"];
                $result["item_count"] = $stmt->rowCount();
                $result["error"] = null;
                $result["http_code"] = 200;
                
                if($result["item_count"] > 0) {
                    $result["body"] = $stmt->fetchAll(PDO::FETCH_OBJ);
                } else {
                    $result["body"] = array();
                }
            }
        }

        return $result;
    }

    public function addUser($params)
    {
        $user_model = new UserModel();
        $user_model->username = $params["username"];
        $user_model->email = $params["email"];
        $user_model->password = $params["password"];

        $model_response = $user_model->addUser();
        $result = array();

        if (isset($model_response["error"])) {
            $result["body"] = null;
            $result["error"] = $model_response["error"];
            $result["http_code"] = 500;
        } else {
            if (isset($model_response["data"])) {
                $stmt = $model_response["data"];
                $result["error"] = null;
                $result["http_code"] = 200;
                
                if($model_response["data"] > 0) {
                    $result["body"] = $model_response["data"];
                } else {
                    $result["body"] = -1;
                }
            }
        }

        return $result;
    }
}