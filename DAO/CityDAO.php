<?php
namespace DAO;

use DAO\Connection;
use Models\City;
use \Exception as Exception;

class CityDAO{
    private $connection;
    private $tableName = "ciudad";

    public function getById($id){
        $query = "SELECT ciudad_nombre FROM ".$this->tableName." where id=$id";
        try
            {
                $this->connection = Connection::GetInstance();
                $resultSet = $this->connection->Execute($query);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        $city = new City($id,$resultSet[0]["ciudad_nombre"]);
        return $city;
    }

    public function getAll(){
        $query = "SELECT * FROM ".$this->tableName;
        try
        {
            $this->connection = Connection::getInstance();
            $resultSet = $this->connection->execute($query);
        }
        catch(Exception $ex)
        {
            throw $ex;
        } 
        $citiesList = array();
        foreach ($resultSet as $row)
        {                
            $city = new City($row["id"],$row["ciudad_nombre"]);
            array_push($citiesList, $city);
        }
        return $citiesList;
    }

    public function getByProvinceId($id){
        $query="SELECT id,ciudad_nombre from ciudad where provincia_id=:id order by ciudad_nombre asc;";
        $param["id"]=$id;
        try{
            $this->connection=Connection::GetInstance();
            $results=$this->connection->execute($query,$param);
            
        }
        catch(Exception $ex){
            throw $ex;
        }
        foreach ($results as $row) {
            $cities[]=new City($row["id"],$row["ciudad_nombre"]);
        }
        return $cities;
    }
}

?>