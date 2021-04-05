<?php

namespace Controllers;
use DAO\RoomDAO;
use \Exception as Exception;

class RoomController{
    private $roomDao;

    public function __construct() {
        $this->roomDao = new RoomDAO();
    }

    /**
     * agrega la habitacion, necesita la id del cine al que lo va a agregar
     */
    public function add ($capacity,$ticketPrice,$description,$cinemaId)
    {
        $message="";
        try{
            if($capacity>0 && $ticketPrice >=0){
                $this->roomDao->add($capacity,$ticketPrice,$description,$cinemaId);             
            }
            else{
                $message="Valor no permitido.";
            }
        }
        catch(Exception $e){
            $message="Error adding the room.";
        }
        finally{
            $this->showRoom($cinemaId,$message);
        }
    }

    public function remove($id,$cinemaId)
    {
        $message="";
        try{
            $this->roomDao->remove($id);
        }
        catch(Exception $e){
            $message="Error removing the room.";
        }
        finally{
            $this->showRoom($cinemaId,$message);
        }
    }

 
    public function modify($capacity,$ticketPrice,$description,$roomId,$cinemaId){
        $message="";
        if($capacity>0 && $ticketPrice >=0){
            try{
                $this->roomDao->modify($roomId,$capacity,$ticketPrice,$description);
            }
            catch(Exception $e){
                $message="Error modifying the room.";
                $this->showRoom($cinemaId);
            }
        }
        $this->showRoom($cinemaId,$message);
    }

    /**
     * una sala en especifico
     */
    public function getById($id){
        try{
            return $this->roomDao->getById($id);
        }
        catch(Exception $e){
            $message="Error getting the room.";

        }
    }

    public function showRoom($cinemaId,$message="")
    {
        $rooms=array();
        try{
            $rooms=$this->getArrayByCinemaId($cinemaId);
        }
        catch(Exception $e){
            $message="Error getting the rooms.";
        }
        finally{
            include VIEWS_PATH."room_admin.php";
        }
    }

    /**
     * todas las salas de un cine
     */
    public function getArrayByCinemaId($cinemaId){
        try{
            return $this->roomDao->getArrayByCinemaId($cinemaId);
        }
        catch(Exception $e){
            throw $e;
        }
    }
 
}

?>