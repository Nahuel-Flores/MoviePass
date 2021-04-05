<?php
namespace Models;

use JsonSerializable;

class Projection implements JsonSerializable
{
    private $id;
    private $movie;
    private $date;
    private $hour;
    private $room;

    public function __construct($id,$movie,$date,$hour,$room) {
        $this->id = $id;
        $this->movie = $movie;
        $this->date = $date;
        $this->hour = $hour;
        $this->room = $room;
    }


    public function getId(){return $this->id;}
    public function getMovie(){return $this->movie;}
    public function getDate(){return $this->date;}
    public function getHour(){return $this->hour;}
    public function getRoom(){return $this->room;}

    public function setMovie($movie){$this->movie = $movie;}
    public function SetId($id){$this->id = $id;}
    public function setDate($date){$this->date = $date;}
    public function setHour($hour){$this->hour = $hour;}
    public function setRoom($room){$this->room = $room;}
    
    public function jsonSerialize()
    {
        $vars=get_object_vars($this);
        return $vars;
    }
    

}
?>