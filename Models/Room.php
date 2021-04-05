<?php

namespace Models;

use JsonSerializable;

class Room implements JsonSerializable
{
    private $id;
    private $capacity;
    private $ticketPrice;
    private $description;
    private $cinema;

    public function __construct($id,$capacity,$ticketPrice,$description,$cinema) {
        $this->id = $id;
        $this->capacity = $capacity;
        $this->ticketPrice = $ticketPrice;
        $this->description = $description;
        $this->cinema=$cinema;
    }

    public function getId(){return $this->id;}
    public function getCapacity(){return $this->capacity;}
    public function getTicketPrice(){return $this->ticketPrice;}
    public function getDescription(){return $this->description;}
    public function getCinema(){return $this->cinema;}

    public function setId ($id){$this->id = ($id);}
    public function setCapacity ($capacity) {$this->capacity = $capacity;}
    public function setTicketPrice ($ticketPrice) {$this->ticketPrice = $ticketPrice;}
    public function setDescription ($description) {$this->description = $description;}
    public function setCinema ($cinema) {$this->cinema = $cinema;}
    
    public function jsonSerialize()
    {
        $vars=get_object_vars($this);
        return $vars;
    }
    
}