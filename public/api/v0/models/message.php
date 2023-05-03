<?php

class MessageModel
{
    private $db_table;
    public $chat_gmember_id;
    public $chat_group_id;
    public $message;
    public $sent_datetime;

    public function __construct()
    {
        $this->db_table = "message";
    }

    private function sanitizeParams()
    {
        if (isset($this->chat_gmember_id)) {
            $this->chat_gmember_id = xssClean($this->chat_gmember_id);
        }
        if (isset($this->chat_group_id)) {
            $this->chat_group_id = xssClean($this->chat_group_id);
        }
        if (isset($this->message)) {
            $this->message = xssClean($this->message);
        }
        if (isset($this->sent_datetime)) {
            $this->sent_datetime = xssClean($this->sent_datetime);
        }
    }

    private function bindParams($_stmt)
    {
        if (isset($this->chat_gmember_id)) {
            $_stmt->bindParam(":CHAT_GMEMBER_ID", $this->chat_gmember_id);
        }
        if (isset($this->chat_group_id)) {
            $_stmt->bindParam(":CHAT_GROUP_ID", $this->chat_group_id);
        }
        if (isset($this->message)) {
            $_stmt->bindParam(":MESSAGE", $this->message);
        }
        if (isset($this->sent_datetime)) {
            $_stmt->bindParam(":SENT_DATETIME", $this->sent_datetime);
        }
    }

    public function sendMessage()
    {
        $db = new Database();
        $conn = null;
        $result = array();

        try {
            $conn = $db->connect();
            date_default_timezone_set("Europe/Istanbul");
            $current_date = date('Y-m-d H:i:s');

            $sqlQuery = "INSERT INTO " . $this->db_table .
                "(CHAT_GMEMBER_ID, CHAT_GROUP_ID, MESSAGE, SENT_DATETIME) VALUES(
                :CHAT_GMEMBER_ID,
                :CHAT_GROUP_ID,
                :MESSAGE,
                '$current_date'
            )";
            $stmt = $conn->prepare($sqlQuery);
            $this->sanitizeParams();
            $this->bindParams($stmt);

            $result["data"] = -1;
            $result["error"] = "Error! Creation Failed";

            if ($stmt->execute()) {
                $result["data"] = 1;
                $result["error"] = null;
            }
        } catch (PDOException $e) {
            $result["data"] = null;
            $result["error"] = $e->errorInfo[count($e->errorInfo) - 1];
        }

        return $result;
    }

    public function getMessagesByGroupId()
    {
        $db = new Database();
        $conn = null;
        $result = array();

        try {
            $conn = $db->connect();

            $sqlQuery = "SELECT CHAT_GMEMBER_ID,
                                CHAT_GROUP_ID,
                                MESSAGE,
                                SENT_DATETIME
                        FROM $this->db_table
                        WHERE CHAT_GROUP_ID = :CHAT_GROUP_ID";
            $stmt = $conn->prepare($sqlQuery);
            $this->sanitizeParams();
            $this->bindParams($stmt);
            $stmt->execute();

            $result["data"] = $stmt->fetchAll(PDO::FETCH_OBJ);
            $result["count"] = $stmt->rowCount();
            $result["error"] = null;
        } catch (PDOException $e) {
            $result["data"] = null;
            $result["error"] = $e->errorInfo[count($e->errorInfo) - 1];
        }

        return $result;
    }
}