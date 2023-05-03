<?php
class ChatGroupController
{
    public function getChatGroup($param_chat_group_id)
    {
        $chat_group = new ChatGroupModel();
        $chat_group->chat_group_id = $param_chat_group_id;
        $model_response = $chat_group->getChatGroup();
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

    public function addChatGroup($params)
    {
        $chat_group_model = new ChatGroupModel();
        $chat_group_model->name = $params["name"];
        $chat_group_model->created_by = $params["created_by"]; // search for id and insert

        $model_response = $chat_group_model->addChatGroup();
        $result = array();

        if (isset($model_response["error"])) {
            $result["body"] = null;
            $result["error"] = $model_response["error"];
            $result["http_code"] = 500;
        } else {
            if (isset($model_response["data"])) {
                $result["error"] = null;
                $result["http_code"] = 200;

                if ($model_response["data"] > 0) {
                    $result["body"] = $model_response["data"];
                } else {
                    $result["body"] = -1;
                }
            }
        }

        return $result;
    }
}