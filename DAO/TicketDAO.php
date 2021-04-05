<?php 

namespace DAO;

use DAO\Connection;
use Models\Ticket;
use \Exception as Exception;


class TicketDAO{
    private $connection;
    private $tableName = "tickets";

    public function add($num,$idProj,$idPurchase){
        $query="INSERT INTO $this->tableName (nro_ticket,id_proj,id_purchase) VALUES(:nro_ticket,:id_proj,:id_purchase)";
        $params["nro_ticket"]=$num;
        $params["id_proj"]=$idProj;
        $params["id_purchase"]=$idPurchase;
        try {
            $this->connection = Connection::getInstance();
            $this->connection->executeNonQuery($query,$params);
            return $this->connection->lastInsertId();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getSoldTicketsByProjId($idProj){
        $query="SELECT count(*) as count from $this->tableName where id_proj=$idProj";
        try {
            $this->connection = Connection::getInstance();
            $results=$this->connection->execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }
        return $results[0]["count"];
    }

    public function getByProjId($idProj){
        $query="SELECT * from $this->tableName where id_proj=$idProj";
        try {
            $this->connection = Connection::getInstance();
            $results=$this->connection->execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }
        $newArr=array();
        foreach ($results as $value) {
            $newArr[]=new Ticket($value["id_ticket"],$value["nro_ticket"]);
        }
        return $newArr;
    }

    public function getProjIdByTicketId($idTicket){
        $query="SELECT t.id_proj from $this->tableName t where id_ticket=$idTicket";
        try {
            $this->connection = Connection::getInstance();
            $results=$this->connection->execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }
        return $results[0]["id_proj"];
    }

    public function getByUserId($usrId){
        $query="SELECT * from $this->tableName t
        inner join purchases p on t.id_purchase=p.id_purchase
        where p.id_user=$usrId";
        try {
            $this->connection = Connection::getInstance();
            $results=$this->connection->execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }
        $newArr=array();
        foreach ($results as $ticket) {
            $newArr[]=new Ticket($ticket["id_ticket"],$ticket["nro_ticket"]);
        }
        return $newArr;
    }

    public function getByPurchaseId($idPurchase){
        $query="SELECT * from $this->tableName where id_purchase=$idPurchase";
        try {
            $this->connection = Connection::getInstance();
            $results=$this->connection->execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }
        $newArr=array();
        foreach ($results as $value) {
            $newArr[]=new Ticket($value["id_ticket"],$value["nro_ticket"]);
        }
        return $newArr;
    }

}

?>