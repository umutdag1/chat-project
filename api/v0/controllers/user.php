<?php
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
                $data = $model_response;
                $result["item_count"] = $data["count"];
                $result["error"] = null;
                $result["http_code"] = 200;
                
                if($result["item_count"] > 0) {
                    $result["body"] = $data["data"];
                } else {
                    $result["body"] = array();
                    $result["error"] = "Error! Not Found";
                    $result["http_code"] = 404;
                }
            }
        }

        return $result;
    }
    public function getUser($param_user_id)
    {
        $user_model = new UserModel();
        $user_model->user_id = $param_user_id;
        $model_response = $user_model->getUser();
        $result = array();

        if (isset($model_response["error"])) {
            $result["body"] = null;
            $result["error"] = $model_response["error"];
            $result["http_code"] = 500;
        } else {
            if (isset($model_response["data"])) {
                $data = $model_response;
                $result["item_count"] = $data["count"];
                $result["error"] = null;
                $result["http_code"] = 200;
                
                if($result["item_count"] > 0) {
                    $result["body"] = $data["data"];
                } else {
                    $result["body"] = array();
                    $result["error"] = "Error! Not Found";
                    $result["http_code"] = 404;
                }
            }
        }

        return $result;
    }

    public function updateUserAvailability($params)
    {
        $user_model = new UserModel();
        $user_model->user_id = $params["user_id"];
        $user_model->banned_datetime = $params["banned_datetime"];
        $user_model->last_online_datetime = $params["last_online_datetime"];
        $user_model->is_online = $params["is_online"];
        $user_model->is_banned = $params["is_banned"];
        $user_model->status = $params["status"];

        $model_response = $user_model->updateUserAvailability();
        $result = array();

        if (isset($model_response["error"])) {
            $result["body"] = null;
            $result["error"] = $model_response["error"];
            $result["http_code"] = 500;
        } else {
            if (isset($model_response["data"])) {
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

    public function isAvailable($param_user_id)
    {
        $user_model = new UserModel();
        $user_model->user_id = $param_user_id;
        $user_model->is_online = 1;
        $user_model->is_banned = 0;
        $user_model->status = 1;
        $model_response = $user_model->getUser();
        $result = array();

        if (isset($model_response["error"])) {
            $result["body"] = null;
            $result["error"] = $model_response["error"];
            $result["http_code"] = 500;
        } else {
            if (isset($model_response["data"])) {
                $data = $model_response;
                $result["item_count"] = $data["count"];
                $result["error"] = null;
                $result["http_code"] = 200;
                
                if($result["item_count"] > 0) {
                    $result["body"] = $data["data"];
                } else {
                    $result["body"] = array();
                    $result["error"] = "Error! Not Found";
                    $result["http_code"] = 404;
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