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
    public $e_mail;
    public $contact_address;
    public $type;
    public $commit;
    public $state;


    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    // signup user
    function create_troubles(){
    
        if($this->isAlreadyExist()){
            return false;
        }
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    num_chantier=:num_chantier, created=:created, name=:name, contact_name=:contact_name, contact_phone=:contact_phone, e_mail=:e_mail, contact_address=:contact_address, commit=:commit, type=:type; state=:state";
    
        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        if(isset($this->num_chantier) and !empty($this->num_chantier)) {
            $this->num_chantier=htmlspecialchars(strip_tags($this->num_chantier));
        } else {
            $this->num_chantier=NULL;
        }
        $this->created=htmlspecialchars(strip_tags($this->created));
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->contact_name=htmlspecialchars(strip_tags($this->contact_name));
        $this->contact_phone=htmlspecialchars(strip_tags($this->contact_phone));
        $this->e_mail=htmlspecialchars(strip_tags($this->e_mail));
        $this->contact_address=htmlspecialchars(strip_tags($this->contact_address));
        $this->type=htmlspecialchars(strip_tags($this->type));
        if(isset($this->commit) and !empty($this->commit)) {
            $this->commit=htmlspecialchars(strip_tags($this->commit));
        } else {
            $this->commit=NULL;
        }
        //$this->commit=htmlspecialchars(strip_tags($this->commit));
        $this->state=htmlspecialchars(strip_tags($this->state));
    
        // bind values
        $stmt->bindParam(":num_chantier", $this->num_chantier);
        $stmt->bindParam(":created", $this->created);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":contact_name", $this->contact_name);
        $stmt->bindParam(":contact_phone", $this->contact_phone);
        $stmt->bindParam(":e_mail", $this->e_mail);
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
    function isAlreadyExist(){
        $query = "SELECT *
            FROM
                " . $this->table_name . " 
            WHERE
                num_chantier ='" . $this->num_chantier . "'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        if($stmt->rowCount() == 0 or $this->num_chantier == "0"){
            return false;
        } else {
            return true;
        }
    }
}


class DeleteTroubles{

    // database connection and table name
    private $conn;
    private $table_name = "chantiers";
    
    // delete troubles record for given id
    public function delete_troubles($id)
    {
        $query = "SELECT * FROM chantiers";
        $result = $this->conn->prepare($query);
        $result->execute();
        return $result;
    }
}
?>