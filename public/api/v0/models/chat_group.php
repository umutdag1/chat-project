<?php
class ChatGroupModel
{
    private $db_table;
    public $chat_group_id;
    public $name;
    public $created_by;
    public $status;

    public function __construct()
    {
        $this->db_table = "chat_group";
    }

    private function sanitizeParams()
    {
        if (isset($this->chat_group_id)) {
            $this->chat_group_id = xssClean($this->chat_group_id);
        }
        if (isset($this->name)) {
            $this->name = xssClean($this->name);
        }
        if (isset($this->created_by)) {
            $this->created_by = xssClean($this->created_by);
        }
        if (isset($this->status)) {
            $this->status = xssClean($this->status);
        }
    }

    private function bindParams($_stmt)
    {
        if (isset($this->chat_group_id)) {
            $_stmt->bindParam(":CHAT_GROUP_ID", $this->chat_group_id);
        }
        if (isset($this->name)) {
            $_stmt->bindParam(":NAME", $this->name);
        }
        if (isset($this->created_by)) {
            $_stmt->bindParam(":CREATED_BY", $this->created_by);
        }
        if (isset($this->status)) {
            $_stmt->bindParam(":STATUS", $this->status);
        }
    }
    public function addChatGroup()
    {
        $db = new Database();
        $conn = null;
        $result = array();

        try {
            $conn = $db->connect();

            $sqlQuery = "INSERT INTO " . $this->db_table .
                "(NAME, CREATED_BY, STATUS) VALUES(
                :NAME,
                :CREATED_BY,
                1
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
}