<?php
Class Role {

    private $db;
    private $select;
    private $selectById;
    
    public function __construct($db){
        $this->db = $db;
        $this->select = $db->prepare("select id,libelle from role order by libelle");
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
}
?>