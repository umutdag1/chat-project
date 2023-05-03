<?php

class ChatGroupMemberModel
{
    private $db_table;
    public $chat_gmember_id;
    public $chat_group_id;
    public $user_id;
    public $join_datetime;
    public $leave_datetime;
    public $is_blocked;
    public $is_admin;

    public function __construct()
    {
        $this->db_table = "chat_group_member";
    }

    private function sanitizeParams()
    {
        if (isset($this->chat_gmember_id)) {
            $this->chat_gmember_id = xssClean($this->chat_gmember_id);
        }
        if (isset($this->chat_group_id)) {
            $this->chat_group_id = xssClean($this->chat_group_id);
        }
        if (isset($this->user_id)) {
            $this->user_id = xssClean($this->user_id);
        }
        if (isset($this->join_datetime)) {
            $this->join_datetime = xssClean($this->join_datetime);
        }
        if (isset($this->leave_datetime)) {
            $this->leave_datetime = xssClean($this->leave_datetime);
        }
        if (isset($this->is_blocked)) {
            $this->is_blocked = xssClean($this->is_blocked);
        }
        if (isset($this->is_admin)) {
            $this->is_admin = xssClean($this->is_admin);
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
        if (isset($this->user_id)) {
            $_stmt->bindParam(":USER_ID", $this->user_id);
        }
        if (isset($this->join_datetime)) {
            $_stmt->bindParam(":JOIN_DATETIME", $this->join_datetime);
        }
        if (isset($this->leave_datetime)) {
            $_stmt->bindParam(":LEAVE_DATETIME", $this->leave_datetime);
        }
        if (isset($this->is_blocked)) {
            $_stmt->bindParam(":IS_BLOCKED", $this->is_blocked);
        }
        if (isset($this->is_admin)) {
            $_stmt->bindParam(":IS_ADMIN", $this->is_admin);
        }
    }

    public function addGroupMember()
    {
        $db = new Database();
        $conn = null;
        $result = array();

        try {
            $conn = $db->connect();
            date_default_timezone_set("Europe/Istanbul");
            $current_date = date('Y-m-d H:i:s');

            $sqlQuery = "INSERT INTO " . $this->db_table .
                "(CHAT_GROUP_ID, USER_ID, JOIN_DATETIME, IS_BLOCKED, IS_ADMIN) VALUES(
                :CHAT_GROUP_ID,
                :USER_ID,
                '$current_date',
                0,
                0
            )";
            $stmt = $conn->prepare($sqlQuery);
            $this->sanitizeParams();
            $this->bindParams($stmt);

            $result["data"] = -1;
            $result["error"] = "Error! Creation Failed";

            if ($stmt->execute()) {
                $result["data"] = $conn->lastInsertId();
                $result["error"] = null;
            }
        } catch (PDOException $e) {
            $result["data"] = null;
            $result["error"] = $e->errorInfo[count($e->errorInfo) - 1];
        }

        return $result;
    }

    public function getGroupMemberByUserId()
    {
        $db = new Database();
        $conn = null;
        $result = array();

        try {
            $conn = $db->connect();

            $sqlQuery = "SELECT CHAT_GMEMBER_ID,
                                CHAT_GROUP_ID,
                                USER_ID, 
                                JOIN_DATETIME, 
                                IS_BLOCKED,
                                IS_ADMIN  
                         FROM $this->db_table WHERE USER_ID = :USER_ID AND 
                                                    CHAT_GROUP_ID = :CHAT_GROUP_ID";
            $stmt = $conn->prepare($sqlQuery);
            $this->sanitizeParams();
            $this->bindParams($stmt);
            $stmt->execute();

            $result["data"] = $stmt->fetch(PDO::FETCH_OBJ);
            $result["count"] = $stmt->rowCount();
            $result["error"] = null;
        } catch (PDOException $e) {
            $result["data"] = null;
            $result["error"] = $e->errorInfo[count($e->errorInfo) - 1];
        }

        return $result;
    }

    public function getGroupMember()
    {
        $db = new Database();
        $conn = null;
        $result = array();

        try {
            $conn = $db->connect();

            $sqlQuery = "SELECT CHAT_GMEMBER_ID,
                                CHAT_GROUP_ID,
                                USER_ID, 
                                JOIN_DATETIME, 
                                IS_BLOCKED,
                                IS_ADMIN  
                         FROM $this->db_table WHERE CHAT_GMEMBER_ID = :CHAT_GMEMBER_ID";
            $stmt = $conn->prepare($sqlQuery);
            $this->sanitizeParams();
            $this->bindParams($stmt);
            $stmt->execute();

            $result["data"] = $stmt->fetch(PDO::FETCH_OBJ);
            $result["count"] = $stmt->rowCount();
            $result["error"] = null;
        } catch (PDOException $e) {
            $result["data"] = null;
            $result["error"] = $e->errorInfo[count($e->errorInfo) - 1];
        }

        return $result;
    }

    public function getUsers()
    {
        $db = new Database();
        $conn = null;
        $result = array();

        try {
            $conn = $db->connect();

            $sqlQuery = "SELECT USER_ID,
                                USERNAME, 
                                EMAIL, 
                                CREATED_DATETIME,
                                BANNED_DATETIME,
                                LAST_ONLINE_DATETIME,
                                IS_ONLINE,
                                IS_BANNED,
                                STATUS  
                        FROM $this->db_table";
            $stmt = $conn->prepare($sqlQuery);
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