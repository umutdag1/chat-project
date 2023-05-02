<?php
class ChatGroupController
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

                if ($result["item_count"] > 0) {
                    $result["body"] = $stmt->fetchAll(PDO::FETCH_OBJ);
                } else {
                    $result["body"] = array();
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