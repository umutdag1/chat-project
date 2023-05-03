<?php
class MessageController
{
    public function getMessagesByGroupId($param_chat_group_id)
    {
        $message = new MessageModel();
        $message->chat_group_id = $param_chat_group_id;
        $model_response = $message->getMessagesByGroupId();
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

    public function sendMessage($params)
    {
        $message = new MessageModel();
        $message->chat_gmember_id = $params["chat_gmember_id"];
        $message->chat_group_id = $params["chat_group_id"];
        $message->message = $params["message"];

        $model_response = $message->sendMessage();
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