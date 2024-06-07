<?php
Class Perso {

    private $db;
    private $insert;
    private $select;
    private $selectById;
    private $selectByIdCat;
    private $update;
    private $delete;
    private $selectLimit;
    private $selectCount; 
    private $recherche; 




    public function __construct($db){
        $this->db = $db;
        $this->insert = $this->db->prepare("insert into personnage(nom, titre, idType, idRarete, photo) values (:nom, :titre, :type, :rarete, :photo)");
        $this->select = $db->prepare("select p.id, nom, titre, t.libelle as type, r.libelle as rarete, p.photo, from personnage p, type t, rarete r where p.idType = t.id and p.idRarete = r.id");    
        $this->selectById = $db->prepare("select p.id, p.nom, p.titre, t.libelle as type, r.libelle as rarete, p.photo from personnage p join type t ON p.idType = t.id join rarete r ON p.idRarete = r.id where p.id=:id");
        $this->selectByIdCat = $db->prepare("select p.id, p.nom, p.titre, t.libelle as type, r.libelle as rarete, c.id as cat_id, c.libelle as cat ,p.photo from personnage p join type t ON p.idType = t.id join rarete r ON p.idRarete = r.id JOIN JSON_TABLE(p.idCat, '$[*]' COLUMNS (cat_id INT PATH '$')) jt JOIN categorie c ON jt.cat_id = c.id where p.id=:id");
        $this->update = $db->prepare("update personnage set nom=:nom, titre=:titre, idType=:type, idRarete=:rarete, photo=:photo where id=:id");
        $this->delete = $db->prepare("delete from personnage where id=:id");
        $this->selectLimit = $db->prepare("select p.id, p.nom, p.titre, t.libelle as type, r.libelle as rarete, p.photo from personnage p join type t ON p.idType = t.id join rarete r ON p.idRarete = r.id order by p.id limit :inf,:limite");
        $this->selectCount =$db->prepare("select count(*) as nb from personnage");
        $this->recherche = $db->prepare("select p.id, p.nom, p.titre, t.libelle as type, r.libelle as rarete, photo from personnage p join type t ON p.idType = t.id join rarete r ON p.idRarete = r.id where (nom like :recherche or titre like :recherche) order by id");

    }
    
    public function insert($nom, $titre, $type, $rarete, $photo) {
        $r = true;
        $this->insert->execute(array(':nom' => $nom, ':titre' => $titre, ':type' => $type, ':rarete' => $rarete, ':photo' => $photo));
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

    public function selectByIdCat($id) {
        $this->selectByIdCat->execute(array(':id' => $id));
        if ($this->selectByIdCat->errorCode() != 0) {
            print_r($this->selectByIdCat->errorInfo());
        }
        return $this->selectByIdCat->fetch();
    }

    public function update($id, $nom, $titre, $type, $rarete, $photo){
        $r = true;
        $this->update->execute(array(':id'=>$id,':nom' => $nom, ':titre' => $titre, ':type' => $type, ':rarete' => $rarete, ':photo' => $photo));
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