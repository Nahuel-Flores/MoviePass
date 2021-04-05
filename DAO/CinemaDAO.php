<?php

namespace DAO;


use Models\Cinema;
use Models\Province;
use Models\City;
use DAO\Connection;
use \Exception as Exception;

class CinemaDAO
{
    private $connection;
    private $tableName = "cinemas";


    public function add($name,$provinceId,$cityId,$address,$userId)
    {
        $id=time(); //number of seconds since January 1 1970
        $query = "INSERT INTO $this->tableName (id_cinema,name_cinema,id_province,id_city,address,id_user) VALUES (:id_cinema,:name_cinema,:id_province,:id_city,:address,:id_user)";
        $parameters["id_cinema"] = $id;
        $parameters["name_cinema"] = $name;
        $parameters["id_province"] = $provinceId;
        $parameters["id_city"] = $cityId;
        $parameters["address"] = $address;
        $parameters["id_user"] = $userId;
        try {
            $this->connection = Connection::getInstance();
            $this->connection->executeNonQuery($query, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getAll()
    {
        $query = "SELECT c.id_cinema,c.name_cinema,p.id as province_id,p.provincia_nombre,ciu.id as ciu_id,ciu.ciudad_nombre,c.address
                from $this->tableName c
                inner join provincia p on c.id_province=p.id
                inner join ciudad ciu on ciu.id=c.id_city";
        try {
            $this->connection = Connection::getInstance();
            $results = $this->connection->execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }
        $cinemasList = array();
        foreach ($results as $row) {
            $prov = new Province($row["province_id"], $row["provincia_nombre"]);
            $ci = new City($row["ciu_id"], $row["ciudad_nombre"]);
            $cinemasList[] = new Cinema(
                $row["name_cinema"],
                $row["id_cinema"],
                $prov,
                $ci,
                $row["address"]);
        }
        return $cinemasList;
    }

    public function modify($modifiedCinema)
    {
        $query = "UPDATE cinemas set name_cinema=:name, id_province=:id_province, id_city=:id_city, address=:address where id_cinema=:id_cinema;";
        $prov = $modifiedCinema->getProvince();
        $city = $modifiedCinema->getCity();
        $params["name"] = $modifiedCinema->getName();
        $params["id_province"] = $prov->getId();
        $params["id_city"] = $city->getId();
        $params["address"] = $modifiedCinema->getAddress();
        $params["id_cinema"] = $modifiedCinema->getId();
        try {
            $this->connection = Connection::getInstance();
            return $this->connection->executeNonQuery($query, $params);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getById($id)
    {
        $query = "SELECT c.name_cinema,c.id_province,p.provincia_nombre,ciu.id as id_city,ciu.ciudad_nombre,c.address from cinemas c
            join  provincia p on p.id=c.id_province
            join ciudad ciu on ciu.id=c.id_city
            where id_cinema=$id";
        try {
            $this->connection = Connection::getInstance();
            $results = $this->connection->execute($query);
            
        } catch (Exception $ex) {
            throw $ex;
        }
        $row = $results[0];
        $prov = new Province($row["id_province"], $row["provincia_nombre"]);
        $ciu = new City($row["id_city"], $row["ciudad_nombre"]);
        $cinema = new Cinema($row["name_cinema"], $id, $prov, $ciu, $row["address"]);
        return $cinema;
    }

    public function getAllByUserId($userId)
    {
        $query = "SELECT c.id_cinema,c.name_cinema,c.id_province,p.provincia_nombre,ciu.id as id_city,ciu.ciudad_nombre,c.address from cinemas c
            join  provincia p on p.id=c.id_province
            join ciudad ciu on ciu.id=c.id_city
            where id_user=$userId";
        try {
            $this->connection = Connection::getInstance();
            $results = $this->connection->execute($query);
            
        } catch (Exception $ex) {
            throw $ex;
        }
        $newArray=array();
        foreach ($results as $value) {
            $prov = new Province($value["id_province"], $value["provincia_nombre"]);
            $ciu = new City($value["id_city"], $value["ciudad_nombre"]);
            $cinema = new Cinema($value["name_cinema"], $value["id_cinema"], $prov, $ciu, $value["address"]);
            $newArray[]=$cinema;
        } 
        return $newArray;
    }


    public function remove($id)
    {
        $query = "DELETE FROM cinemas WHERE id_cinema=$id";
        try {
            $this->connection = Connection::getInstance();
            return $this->connection->executeNonQuery($query);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
?>
