<?php
class Database{
    private $server = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbname = "student_details";
    private $mysqli = "";
    private $result = array();
    private $con = false;
    public function __construct(){
        if(!$this->con){
            $this->mysqli = new mysqli($this->server,$this->user,$this->pass,$this->dbname);
            $this->con = true;
            if($this->mysqli->connect_error){
                array_push($this->result, $this->mysqli->connect_error);
                return true; 
            }
        }else{
            return false;
        }
    }
}

?>