<?php
namespace Controllers;

use DAO\CityDAO;
use DAO\ProvinceDAO;
use \Exception as Exception;

class LocationController{
    private $provinceDao;
    private $cityDao;

    public function __construct() {
        $this->provinceDao=new ProvinceDAO();
        $this->cityDao=new CityDAO();
    }

    public function getAllProvinces(){
        try{
            return $this->provinceDao->GetAll();
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public function getProvinceById($id){
        try{
            return $this->provinceDao->getById($id);
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public function getCityById($id){
        try{
            return $this->cityDao->getById($id);
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public function getCitiesByProvince($idProvince){
        try{
            return $cities=$this->cityDao->getByProvinceId($idProvince);
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public function updateCitiesSelect($provinceId){
        $cities=$this->getCitiesByProvince($provinceId);
        foreach ($cities as $c) {
            $obj["id"]=$c->getId();
            $obj["name"]=$c->getName();
            $toEncode[]=$obj;
        }
        echo json_encode($toEncode,1);
    }
}

?>