<?php

namespace DAO;

use DAO\Connection;
use Models\Province;
use \Exception as Exception;

class ProvinceDAO
{
    private $connection;
    private $tableName = "provincia";

    public function getById($id)
    {
        $query = "SELECT provincia_nombre FROM " . $this->tableName . " where id=$id";
        try {
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }
        $province = new Province($id, $resultSet[0]["provincia_nombre"]);
        return $province;
    }


    public function getAll()
    {
        $query = "SELECT * FROM " . $this->tableName;
        try {
            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }
        $provincesList = array();
        foreach ($resultSet as $row) {
            $province = new Province($row["id"], $row["provincia_nombre"]);
            array_push($provincesList, $province);
        }
        return $provincesList;
    }
}
