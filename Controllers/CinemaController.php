<?php

namespace Controllers;
use Models\Cinema;
use DAO\CinemaDAO;
use Controllers\LocationController;
use \Exception as Exception;

class CinemaController{
    private $cinemaDao,$provinces,$initCities;

    public function __construct() {
        $this->cinemaDao=new CinemaDAO();
        $locationContr=new LocationController();
        try{
            $this->provinces=$locationContr->getAllProvinces();
            $this->initCities=$locationContr->getCitiesByProvince(1);  
        }
        catch(Exception $e){
            $message="Error getting cinemas.";
            $this->showCinemasList();
        }
    }

    /**
     * muestra todos si sos admin, sino muestra solo los cines del usuario dueño
     */
    public function getAll(){
        if(session_status () != 2){
            session_start();  
        }
        try{
            if ($_SESSION["userType"]==3) {
                return $this->cinemaDao->getAll();
            }
            else{
                return $this->getAllByUserId($_SESSION["Id"]);
            }
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public function getAllByUserId($userId){
        try{
            return $this->cinemaDao->getAllByUserId($userId);
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public function getAllSorted(){
        try{
            $sorted=$this->getAll();
            usort($sorted,array("Models\Cinema","compare"));
            return $sorted;
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public function add($name,$provinceId,$cityId,$address){
        session_start();
        $userId=$_SESSION["Id"];
        $message="";
        try{
            $this->cinemaDao->add($name,$provinceId,$cityId,$address,$userId);
        }
        catch(Exception $e){
            $message="error adding cinema.";
        }
        finally{
            $this->showCinemasList($message);
        }
    }

    public function modify($name,$id,$provinceId,$cityId,$address){
        $message="";
        $locContro=new LocationController();
        try{
            $province=$locContro->getProvinceById($provinceId);
            $city=$locContro->getCityById($cityId);
            $this->cinemaDao->modify(new Cinema($name,$id,$province,$city,$address));
        }
        catch(Exception $e){
            $message="error modifying cinema.";
        }
        finally{
            $this->showCinemasList($message);
        }
    }

    public function remove($id){
        $message="";
        try{
            $this->cinemaDao->remove($id);
        }
        catch(Exception $e){
            $message="error removing cinema.";
        }
        finally{
            $this->showCinemasList($message);
        }
    }

    public function showCinemasList($message=""){
        $cinemas=array();
        try{
            $cinemas=$this->getAll();
        }
        catch(Exception $e){
            $message="Error getting cinemas.";
        }
        finally{
            require_once VIEWS_PATH."cinema_list.php";
        }
    }



}


?>