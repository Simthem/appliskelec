<?php
class Troubles{
 
    // database connection and table name
    private $conn;
    private $table_name = "chantiers";
 
    // object properties
    public $id;
    public $num_chantier;
    public $created;
    public $name;
    public $contact_name;
    public $contact_phone;
    public $contact_address;
    public $type;
    public $commit;
    public $state;


    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    // signup user
    function create(){
    
        if($this->isAlreadyExist()){
            return false;
        }
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    num_chantier=:num_chantier, created=:created, name=:name, contact_name=:contact_name, contact_phone=:contact_phone, contact_address=:contact_address, type=:type, commit=:commit, state=:state";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->num_chantier=htmlspecialchars(strip_tags($this->num_chantier));
        $this->created=htmlspecialchars(strip_tags($this->created));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->contact_name=htmlspecialchars(strip_tags($this->contact_name));
        $this->contact_phone=htmlspecialchars(strip_tags($this->contact_phone));
        $this->contact_address=htmlspecialchars(strip_tags($this->contact_address));
        $this->type=htmlspecialchars(strip_tags($this->type));
        $this->commit=htmlspecialchars(strip_tags($this->commit));
        $this->state=htmlspecialchars(strip_tags($this->state));
    
        // bind values
        $stmt->bindParam(":num_chantier", $this->num_chantier);
        $stmt->bindParam(":created", $this->created);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":contact_name", $this->contact_name);
        $stmt->bindParam(":contact_phone", $this->contact_phone);
        $stmt->bindParam(":contact_address", $this->contact_address);
        $stmt->bindParam(":type", $this->type);
        $stmt->bindParam(":commit", $this->commit);
        $stmt->bindParam(":state", $this->state);
    
        // execute query
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }
    
        return false;
        
    }
    /*// login user
    function login(){
        // select all query
        $query = "SELECT
                    `id`, `username`, `password`, `created`
                FROM
                    " . $this->table_name . " 
                WHERE
                    username='".$this->username."' AND password='".$this->password."'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }*/
    function isAlreadyExist(){
        $query = "SELECT *
            FROM
                " . $this->table_name . " 
            WHERE
                num_chantier ='".$this->num_chantier."'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        if($stmt->rowCount() > 0 and $this->num_chantier != 0){
            return true;
        } else {
            return false;
        }
    }
}