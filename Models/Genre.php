<?php

namespace Models;

use JsonSerializable;

class Genre implements JsonSerializable{
    //dejo los atributos publicos porque sino no encodea a json dentro de la pelicula D:
    public $id;
    public $name;

    public function __construct($id,$name) {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function jsonSerialize()
    {
        $vars=get_object_vars($this);
        return $vars;
    }
}

?>