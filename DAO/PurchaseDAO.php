<?php

namespace DAO;

use Models\Purchase as Purchase;
use \Exception as Exception;
use DAO\Connection;

class PurchaseDAO
{
    private $connection;
    private $tableName;

    public function __construct()
    {
        $this->tableName = "purchases";
    }

    public function add($id_user, $quantity_tikets, $discount, $date, $total)
    {
        $query = "INSERT INTO $this->tableName (id_user,quantity_tickets,discount,purchase_date,total) 
                        VALUES (:id_user,:quantity_tickets,:discount,:purchase_date,:total)";
        $parameters["id_user"] = $id_user;
        $parameters["quantity_tickets"] = $quantity_tikets;
        $parameters["discount"] = floatval($discount);
        $parameters["purchase_date"] = $date;
        $parameters["total"] = $total;

        try {
            $this->connection = Connection::getInstance();
            $this->connection->executeNonQuery($query, $parameters);
            return $this->connection->lastInsertId();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * d) Consultar totales vendidos en pesos (por película ó por cine, entre fechas)
     */
    public function totalSoldByMovie($movieId, $date1, $date2)
    {
        $query = "SELECT sum(r.ticket_price) as suma  from purchases p
                    inner join tickets t on t.id_purchase= p.id_purchase
                    inner join projections pr on pr.id_proj=t.id_proj
                    inner join rooms r on pr.id_room= r.id_room
                    where pr.id_movie=$movieId and pr.proj_date between \"$date1\" and \"$date2\"
                    group by id_movie";
        try {
            $this->connection = Connection::getInstance();
            $results = $this->connection->execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }
        if(!empty($results)){
            return $results[0]["suma"];
        }
        else
        {
            return 0;
        }
    }

    public function totalSoldByCinema($cinemaId, $date1, $date2)
    {
        $query = "SELECT sum(r.ticket_price) as suma from purchases p
                    inner join tickets t on t.id_purchase=p.id_purchase
                    inner join projections pr on pr.id_proj=t.id_proj
                    inner join rooms r on r.id_room=pr.id_room
                    inner join cinemas c on c.id_cinema=r.id_cinema
                    where c.id_cinema=$cinemaId and pr.proj_date between \"$date1\" and \"$date2\"
                    group by c.id_cinema";
        try {
            $this->connection = Connection::getInstance();
            $results = $this->connection->execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }
        if(!empty($results)){
            return $results[0]["suma"];
        }
        else
        {
            return 0;
        }
    }

    public function getById($id_purchase)
    {

        $query = "SELECT * from $this->tableName where id_purchase=$id_purchase";

        try {
            $this->connection = Connection::getInstance();
            $results = $this->connection->execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }

        if (!empty($results)) {
            $row = $results[0];

            $purchase = new Purchase($row["id_purchase"], $row["quantity_tickets"], $row["discount"], $row["purchase_date"], $row["total"]);

            return $purchase;
        } else {
            return null;
        }
    }
}
