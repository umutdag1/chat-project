<?php
class ChatGroupMemberController
{
    public function getGroupMemberByUserId($param_user_id, $param_chat_group_id)
    {
        $chat_gmember_model = new ChatGroupMemberModel();
        $chat_gmember_model->user_id = $param_user_id;
        $chat_gmember_model->chat_group_id = $param_chat_group_id;
        $model_response = $chat_gmember_model->getGroupMemberByUserId();
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
    public function getGroupMember($param_chat_gmember_id)
    {
        $chat_gmember_model = new ChatGroupMemberModel();
        $chat_gmember_model->chat_gmember_id = $param_chat_gmember_id;
        $model_response = $chat_gmember_model->getGroupMember();
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

    public function addGroupMember($params)
    {
        $chat_gmember_model = new ChatGroupMemberModel();
        $chat_gmember_model->chat_group_id = $params["chat_group_id"];
        $chat_gmember_model->user_id = $params["user_id"];
        $chat_gmember_model->is_blocked = $params["is_blocked"];
        $chat_gmember_model->is_admin = $params["is_admin"];

        $model_response = $chat_gmember_model->addGroupMember();
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