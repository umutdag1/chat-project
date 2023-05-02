<?php
require __DIR__ . '/../../../../src/config/database.php';
require __DIR__ . '/../../../../src/libs/xssClean.php';
require __DIR__ . '/../../../../src/libs/hashString.php';

class UserModel
{
    private $db_table;
    public $user_id;
    public $username;
    public $email;
    public $password;
    public $created_datetime;
    public $banned_datetime;
    public $last_online_datetime;
    public $is_online;
    public $is_banned;
    public $status;

    public function __construct()
    {
        $this->db_table = "user";
    }

    private function sanitizeParams()
    {
        if (isset($this->user_id)) {
            $this->user_id = xssClean($this->user_id);
        }
        if (isset($this->username)) {
            $this->username = xssClean($this->username);
        }
        if (isset($this->email)) {
            $this->email = strtolower(xssClean($this->email));
        }
        if (isset($this->password)) {
            $this->password = hashPass(xssClean($this->password));
        }
        if (isset($this->created_datetime)) {
            $this->created_datetime = xssClean($this->created_datetime);
        }
        if (isset($this->banned_datetime)) {
            $this->banned_datetime = xssClean($this->banned_datetime);
        }
        if (isset($this->last_online_datetime)) {
            $this->last_online_datetime = xssClean($this->last_online_datetime);
        }
        if (isset($this->is_online)) {
            $this->is_online = xssClean($this->is_online);
        }
        if (isset($this->is_banned)) {
            $this->is_banned = xssClean($this->is_banned);
        }
        if (isset($this->status)) {
            $this->status = xssClean($this->status);
        }
    }

    private function bindParams($_stmt)
    {
        if (isset($this->user_id)) {
            $_stmt->bindParam(":USER_ID", $this->user_id);
        }
        if (isset($this->username)) {
            $_stmt->bindParam(":USERNAME", $this->username);
        }
        if (isset($this->email)) {
            $_stmt->bindParam(":EMAIL", $this->email);
        }
        if (isset($this->password)) {
            $_stmt->bindParam(":PASSWORD", $this->password);
        }
        if (isset($this->created_datetime)) {
            $_stmt->bindParam(":CREATED_DATETIME", $this->created_datetime);
        }
        if (isset($this->banned_datetime)) {
            $_stmt->bindParam(":BANNED_DATETIME", $this->banned_datetime);
        }
        if (isset($this->last_online_datetime)) {
            $_stmt->bindParam(":LAST_ONLINE_DATETIME", $this->last_online_datetime);
        }
        if (isset($this->is_online)) {
            $_stmt->bindParam(":IS_ONLINE", $this->is_online);
        }
        if (isset($this->is_banned)) {
            $_stmt->bindParam(":IS_BANNED", $this->is_banned);
        }
        if (isset($this->status)) {
            $_stmt->bindParam(":STATUS", $this->status);
        }
    }
    public function addUser()
    {
        $db = new Database();
        $conn = null;
        $result = array();

        try {
            $conn = $db->connect();
            date_default_timezone_set("Europe/Istanbul");
            $current_date = date('Y-m-d H:i:s');

            $sqlQuery = "INSERT INTO " . $this->db_table .
                "(USERNAME, EMAIL, PASSWORD, CREATED_DATETIME, IS_ONLINE, IS_BANNED, STATUS) VALUES(
                :USERNAME,
                :EMAIL,
                :PASSWORD,
                '$current_date',
                0,
                0,
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
            $result["error"] = $e->getMessage();
        }

        return $result;
    }

    public function getUserIdByName()
    {
        $db = new Database();
        $conn = null;
        $result = array();

        try {
            $conn = $db->connect();

            $sqlQuery = "SELECT * FROM $this->db_table WHERE USERNAME = ':USERNAME'";
            $stmt = $conn->prepare($sqlQuery);
            $this->sanitizeParams();
            $this->bindParams($stmt);
            $stmt->execute();

            $result["data"] = $stmt;
            $result["error"] = null;
        } catch (PDOException $e) {
            $result["data"] = null;
            $result["error"] = $e->getMessage();
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

            $sqlQuery = "SELECT * FROM $this->db_table";
            $stmt = $conn->prepare($sqlQuery);
            $stmt->execute();

            $result["data"] = $stmt;
            $result["error"] = null;
        } catch (PDOException $e) {
            $result["data"] = null;
            $result["error"] = $e->getMessage();
        }

        return $result;
    }
}