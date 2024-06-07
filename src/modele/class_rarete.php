<?php
Class Rarete {
    
    private $db;
    private $select;
    private $insert;
    private $selectById;
    private $update;
    private $delete;
    private $selectLimit;
    private $selectCount; 
    
    public function __construct($db){
        $this->db = $db;
        $this->insert = $this->db->prepare("insert into rarete(libelle) values (:libelle)");
        $this->select = $db->prepare("select id,libelle from  rarete order by id");
        $this->selectById = $db->prepare("select * from rarete where id=:id");
        $this->update = $db->prepare("update rarete set libelle=:libelle where id=:id");
        $this->delete = $db->prepare("delete from rarete where id=:id");
        $this->selectLimit = $db->prepare("select id, libelle from rarete order by id limit :inf,:limite");
        $this->selectCount =$db->prepare("select count(*) as nb from rarete");
    }

    public function insert($libelle) {
        $r = true;
        $this->insert->execute(array(':libelle' => $libelle));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }    


    public function select(){
        $this->select->execute();
        if ($this->select->errorCode()!=0){
        print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

    public function selectById($id) {
        $this->selectById->execute(array(':id' => $id));
        if ($this->selectById->errorCode() != 0) {
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }

    public function update($id, $libelle){
        $r = true;
        $this->update->execute(array(':id'=>$id,':libelle'=>$libelle));
        if ($this->update->errorCode()!=0){ print_r($this->update->errorInfo());
        $r=false;
        }
        return $r;
    }

    public function delete($id){
        $r = true;
        $this->delete->execute(array(':id'=>$id));
        if ($this->delete->errorCode()!=0){
        print_r($this->delete->errorInfo());
        $r=false;
        }
        return $r;
    }

    public function selectLimit($inf, $limite){
        $this->selectLimit->bindParam(':inf', $inf, PDO::PARAM_INT);
        $this->selectLimit->bindParam(':limite', $limite, PDO::PARAM_INT);
        $this->selectLimit->execute();
        if ($this->selectLimit->errorCode()!=0){
        print_r($this->selectLimit->errorInfo());
        }
        return $this->selectLimit->fetchAll();
    }

    public function selectCount(){
        $this->selectCount->execute();
        if ($this->selectCount->errorCode()!=0){
        print_r($this->selectCount->errorInfo());
        }
        return $this->selectCount->fetch();
    }

}
?>