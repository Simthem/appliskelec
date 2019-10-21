<?php
class Intervention{
 
    // database connection and table intervention_hours
    private $conn;
    private $table = "global_reference";

    // object properties
    public $id;
    public $user_id;
    public $chantier_id;
    public $intervention_hours;
    public $panier_repas;
    public $night_hours;
    public $commit;
    public $created;


    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    // create intervention
    function create(){
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table . "
                SET
                    user_id=:user_id, chantier_id=:chantier_id, intervention_hours=:intervention_hours, panier_repas=:panier_repas, night_hours=:night_hours, commit=:commit, created=:created";
    
        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->chantier_id=htmlspecialchars(strip_tags($this->chantier_id));
        $this->intervention_hours=htmlspecialchars(strip_tags($this->intervention_hours));
        $this->panier_repas=htmlspecialchars(strip_tags($this->panier_repas));
        $this->night_hours=htmlspecialchars(strip_tags($this->night_hours));
        $this->commit=htmlspecialchars(strip_tags($this->commit));
        $this->created=htmlspecialchars(strip_tags($this->created));
    
        // bind values
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":chantier_id", $this->chantier_id);
        $stmt->bindParam(":intervention_hours", $this->intervention_hours);
        $stmt->bindParam(":panier_repas", $this->panier_repas);
        $stmt->bindParam(":night_hours", $this->night_hours);
        $stmt->bindParam(":commit", $this->commit);
        $stmt->bindParam(":created", $this->created);
    
        if ($stmt->execute()) {
            echo "youpiiiii";
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        echo "et merde ..";
        return false;
    }
}