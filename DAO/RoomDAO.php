<?php

namespace DAO;

use DAO\CinemaDAO;
use Models\Room;
use \Exception as Exception;

class RoomDAO {
    private $connection;
    private $cinemaDao;
    private $tableName = "rooms";
    
    public function __construct() {
        $this->cinemaDao = new CinemaDAO();
    }

    public function add($capacity,$ticketPrice,$description,$cinemaId)
    {
        $id = time();
        $query = "INSERT INTO $this->tableName (id_room,id_cinema,capacity,ticket_price,descript) 
                    VALUES (:id_room,:id_cinema,:capacity,:ticket_price,:descript)";           
        $parameters["id_room"] =$id;
        $parameters["id_cinema"] =$cinemaId;
        $parameters["capacity"] =$capacity;
        $parameters["ticket_price"] =$ticketPrice;
        $parameters["descript"] =$description;
        try
        {
            $this->connection = Connection::getInstance();
            $this->connection->executeNonQuery($query, $parameters);
        }
        catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function remove ($id)
    {
        $query = "DELETE FROM rooms WHERE id_room=$id";  
        try
        {
            $this->connection=Connection::getInstance();
            return $this->connection->executeNonQuery($query);
        }
        catch(Exception $ex){
            throw $ex;
        }
    }

    public function modify($roomId,$capacity,$ticketPrice,$description){
        $query="UPDATE rooms set capacity=:capacity,ticket_price=:ticket_price,descript=:descript WHERE id_room=$roomId";
        $params["capacity"]=$capacity;
        $params["ticket_price"]=$ticketPrice;
        $params["descript"]=$description;
        try {
            $this->connection=Connection::getInstance();
            return $this->connection->executeNonQuery($query, $params);
        } 
        catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * todas las salas de un cine
     */
    public function getArrayByCinemaId($cinemaId){
        $query="SELECT * from $this->tableName where id_cinema=$cinemaId";
        try{
            $this->connection=Connection::getInstance();
            $results=$this->connection->execute($query);
            $cinema=$this->cinemaDao->getById($cinemaId);
        }catch(Exception $ex){
            throw $ex;
        }
        $roomList=array();
        foreach ($results as $room) {
            $roomList[]=new Room($room["id_room"],
            $room["capacity"],
            $room["ticket_price"],
            $room["descript"],
            $cinema); 
        }
        return $roomList;
    }

    public function getById($id){
        $query="SELECT * from $this->tableName where id_room=$id";
        try{
            $this->connection=Connection::getInstance();
            $results=$this->connection->execute($query);
            $row=$results[0];
            $cinema=$this->cinemaDao->getById($row["id_cinema"]);
        }catch(Exception $ex){
            throw $ex;
        }
        $room=new Room($row["id_room"],
                $row["capacity"],
                $row["ticket_price"],
                $row["descript"],
                $cinema);
        return $room;
    }
}

?>