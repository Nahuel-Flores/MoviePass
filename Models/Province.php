<?php
namespace Models;

class Province{
    private $id;
    private $name;

    public function __construct($id,$name) {
        $this->id = $id;
        $this->name=$name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }

    public function setId($i){
        $this->id=$i;
    }

    public function setName($name){
        $this->name=$name;
    }
}

?>