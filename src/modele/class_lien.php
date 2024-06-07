<?php
Class Lien {

    private $db;
    private $insert;
    private $select;
    private $selectById;
    private $update;
    private $delete;
    private $selectLimit;
    private $selectCount; 
    private $recherche;
    private $selectIn;



    public function __construct($db){
        $this->db = $db;
        $this->insert = $this->db->prepare("insert into liens(libelle, descrip1, descrip10) values (:libelle, :descrip1, :descrip10)");
        $this->select = $db->prepare("select id, libelle, descrip1, descrip10 from liens order by id");
        $this->selectById = $db->prepare("select id, libelle, descrip1, descrip10 from liens where id=:id");       
        $this->update = $db->prepare("update liens set libelle=:libelle, descrip1=:descrip1, descrip10=:descrip10 where id=:id");
        $this->delete = $db->prepare("delete from liens where id=:id");
        $this->selectLimit = $db->prepare("select id, libelle, descrip1, descrip10 from liens order by id limit :inf,:limite");
        $this->selectCount =$db->prepare("select count(*) as nb from liens");
        $this->recherche = $db->prepare("select p.id,designation,description,prix,photo,t.libelle as type FROM liens p,type t WHERE p.idType = t.id and (designation like :recherche or description like :recherche) order by id");
    }
    
    public function insert($libelle, $descrip1, $descrip10) {
        $r = true;
        $this->insert->execute(array(':libelle' => $libelle, ':descrip1' => $descrip1, ':descrip10' => $descrip10));
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

    public function update($libelle, $descrip1, $descrip10){
        $r = true;
        $this->update->execute(array(':libelle' => $libelle, ':descrip1' => $descrip1, ':descrip10' => $descrip10));
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

    public function recherche($recherche){
        $this->recherche->execute(array('recherche'=>'%'.$recherche.'%'));
        if ($this->recherche->errorCode()!=0){
        print_r($this->recherche->errorInfo());
        }
        return $this->recherche->fetchAll();
    }

}
?>