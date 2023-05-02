<?php
class ChatGroupController
{
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