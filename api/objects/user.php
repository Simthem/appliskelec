<?php
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "users";
 
    // object properties
    public $id;
    public $username;
    public $password;
    public $created;
    public $first_name;
    public $last_name;
    public $e_mail;
    public $phone;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // signup user
    function signup(){
    
        if($this->isAlreadyExist()){
            return false;
        }

        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    first_name=:first_name, last_name=:last_name, e_mail=:e_mail, phone=:phone, username=:username, password=:password, created=:created";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        $this->e_mail=htmlspecialchars(strip_tags($this->e_mail));
        $this->last_name=htmlspecialchars(strip_tags($this->last_name));
        $this->first_name=htmlspecialchars(strip_tags($this->first_name));
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->created=htmlspecialchars(strip_tags($this->created));
    
        // bind values
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":e_mail", $this->e_mail);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":created", $this->created);
    
        // execute query
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }



    // login user

    function login(){

        // select query
        $query = "SELECT
                    `id`, `username`, `password`, `created`
                FROM
                    " . $this->table_name . " 
                WHERE
                    username='" . $this->username . "' AND password='" . $this->password . "'";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        return $stmt;
    }



    function isAlreadyExist(){
        $query = "SELECT *
            FROM
                " . $this->table_name . " 
            WHERE
                username='" . $this->username . "'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0){
            return true;
        }
        else{
            return false;
        }
    }
}



class Admin{

    // database connection and table name
    private $conn;
    private $table_name = "admin";

    // object properties
    public $id;
    public $admin_name;
    public $admin_pass;
    public $created;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function log_admin(){
        // select query
        $query = "SELECT
                    `id`, `admin_name`, `admin_pass`, `created`
                FROM
                    " . $this->table_name . " 
                WHERE
                    admin_name='" . $this->admin_name . "' AND admin_pass='" . $this->admin_pass . "'";
        // prepare query statement
        $sql = $this->conn->prepare($query);
        // execute query
        $sql->execute();
        return $sql;
    }
}



class DeleteUser{

    // database connection and table name
    private $conn;
    private $table_name = "users";
    
    // delete user record for given id
    public function delete_user($id)
    {
        $query = "SELECT * FROM users";
        $result = $this->conn->prepare($query);
        $result->execute();
        return $result;
    }
}
?>