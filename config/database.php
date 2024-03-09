<?php
class Database
{
    private $server = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbname = "student_details";
    private $mysqli = "";
    private $result = array();
    private $con = false;
    public function __construct()
    {
        if (!$this->con) {
            $this->mysqli = new mysqli($this->server, $this->user, $this->pass, $this->dbname);
            $this->con = true;
            if ($this->mysqli->connect_error) {
                array_push($this->result, $this->mysqli->connect_error);
                return true;
            }
        } else {
            return false;
        }
    }
    // insert
    public function insert($table, $param = array())
    {
        if ($this->tableExists($table)) {
            $table_columns = implode(',', array_keys($param));
            $table_values = implode(",", $param);
            $sql = "INSERT INTO $table($table_columns)VALUES('$table_values')";
            if ($this->mysqli->query($sql)) {
                array_push($this->result, $this->mysqli->insert_id);
                return true;
            } else {
                array_push($this->result, $this->mysqli->error);
                return false;
            }
        } else {
            return false;
        }
    }

    // GET
    public function select($table, $row = "*", $join = null, $where = null, $order = null, $limit = null, $id = null)
    {
        if ($this->tableExists($table)) {
            $sql = "SELECT $row FROM $table";
            if ($join != null) {
                $sql .= "JOIN $join";
            }
            if ($where != null && $id !== null) {
                $sql .= "WHERE $where = '$id'";
            }
            if ($order != null) {
                $sql .= "OEDER $join";
            }
            if ($limit != null) {
                $sql .= "LIMIT $limit";
            }
            $query = $this->mysqli->query($sql);
            if ($query) {
                $this->result = $query->fetch_all(MYSQLI_ASSOC);
                return true;
            } else {
                array_push($this->result, $this->mysqli->error);
                return false;
            }
        }
    }
    // SHOW
    public function sql($sql)
    {
        $query = $this->mysqli->query($sql);
        if ($query) {
            $this->result = $query->fetch_all(MYSQLI_ASSOC);
            return true;
        } else {
            array_push($this->result, $this->mysqli->error);
            return false;
        }
    }
    // UPDATE
    public function update($table, $params = array(), $where = null, $id = null)
    {
        if ($this->tableExists($table)) {
            $args = array();
            foreach ($params as $key => $value) {
                $args[] = "$key = '$value'";
            }
            $sql = "UPDATE $table SET" . implode(',', $args);
            if ($where != null && $id != null) {
                $sql .= "WHERE $where = '$id'";
            }
            if ($this->mysqli->query($sql)) {
                array_push($this->result, $this->mysqli->affected_rows);
                return true;
            } else {
                array_push($this->result, $this->mysqli->error);
                return false;
            }
        } else {
            return false;
        }

    }
    // DELETE
    public function delete($table, $id)
    {
        if ($this->tableExists($table)) {
            $sql = "DELETE FROM $table where `id` = '$id'";

            if ($this->mysqli->query($sql)) {
                array_push($this->result, $this->mysqli->affected_rows);
                return true;
            } else {
                array_push($this->result, $this->mysqli->error);
                return false;
            }
        } else {
            return false;
        }
    }
    // table exist or not
    public function tableExists($table)
    {
        $sql = "SHOW TABLES FROM $this->dbname LIKE '$table'";
        $tableINdb = $this->mysqli->query($sql);
        if ($tableINdb) {
            if ($tableINdb->num_rows == 1) {
                return true;
            } else {
                array_push($this->result, $table . "Does not exist in database.");
                return false;
            }
        }
    }
    // GET RESULT
    public function getResult()
    {
        $val = $this->result;
        $this->result = array();
        return $val;
    }
}
?>