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
    public $absence;
    public $commit;
    public $created;
    public $updated;
    public $state;


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
                    user_id=:user_id, chantier_id=:chantier_id, intervention_hours=:intervention_hours, panier_repas=:panier_repas, night_hours=:night_hours, absence=:absence, commit=:commit, created=:created, updated=:updated, state=:state";
    
        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->chantier_id=htmlspecialchars(strip_tags($this->chantier_id));
        if (isset($this->intervention_hours) and !empty($this->intervention_hours) and ($this->intervention_hours) != 0) {
            $this->intervention_hours=htmlspecialchars(strip_tags($this->intervention_hours));
        } else {
            $this->intervention_hours=NULL;
        }
        $this->panier_repas=htmlspecialchars(strip_tags($this->panier_repas));
        if (isset($this->night_hours) and !empty($this->night_hours) and ($this->night_hours) != 0) {
            $this->night_hours=htmlspecialchars(strip_tags($this->night_hours));
        } else {
            $this->night_hours=NULL;
        }
        if (isset($this->absence) and !empty($this->absence) and ($this->absence) != 0) {
            $this->absence=htmlspecialchars(strip_tags($this->absence));
        } else {
            $this->absence=NULL;
        }
        if (isset($this->commit) and !empty($this->commit)) {
            $this->commit=htmlspecialchars(strip_tags($this->commit));
        } else {
            $this->commit=NULL;
        }
        //$this->commit=htmlspecialchars(strip_tags($this->commit));
        $this->created=htmlspecialchars(strip_tags($this->created));
        $this->updated=htmlspecialchars(strip_tags($this->updated));
        $this->state=htmlspecialchars(strip_tags($this->state));
    
        // bind values
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":chantier_id", $this->chantier_id);
        $stmt->bindParam(":intervention_hours", $this->intervention_hours);
        $stmt->bindParam(":panier_repas", $this->panier_repas);
        $stmt->bindParam(":night_hours", $this->night_hours);
        $stmt->bindParam(":absence", $this->absence);
        $stmt->bindParam(":commit", $this->commit);
        $stmt->bindParam(":created", $this->created);
        $stmt->bindParam(":updated", $this->updated);
        $stmt->bindParam(":state", $this->state);
    
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }
}