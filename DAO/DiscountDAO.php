<?php 

namespace DAO;
use DAO\Connection;
use Models\Discount;
use Models\CreditAccount;
use \Exception as Exception;

class DiscountDAO{
    private $connection;
    private $tableName = "discounts";

    public function add($percent,$date,$creditAccountId)
    {
        $query = "call select_or_insert(\"$date\",$creditAccountId,$percent)";
        try {
            $this->connection = Connection::getInstance();
            $this->connection->executeNonQuery($query);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getAll(){
        $query="SELECT * from $this->tableName d
                inner join creditAccounts c on c.id_creditAccount=d.id_creditAccount";
        try{
            $this->connection = Connection::getInstance();
            $results=$this->connection->execute($query);
        }
        catch (Exception $ex) {
            throw $ex;
        }
        $disArray=array();
        foreach ($results as $row) {
            $creditAccount=new CreditAccount($row["id_creditAccount"],$row["company"]);
            $disArray[]=new Discount($row["id_discount"],$row["dis_perc"],$row["dis_date"],$creditAccount);
        }
        return $disArray;
    }

    public function getByDate($date){
        $query="SELECT * from $this->tableName d
                inner join creditAccounts c on c.id_creditAccount=d.id_creditAccount
                where d.dis_date=\"$date\"";
        try{
            $this->connection = Connection::getInstance();
            $results=$this->connection->execute($query);
        }
        catch (Exception $ex) {
            throw $ex;
        }
        $disArray=array();
        foreach ($results as $row) {
            $creditAccount=new CreditAccount($row["id_creditAccount"],$row["company"]);
            $disArray[]=new Discount($row["id_discount"],$row["dis_perc"],$row["dis_date"],$creditAccount);
        }
        return $disArray;
    }

    public function getByDateAccount($date,$accountId){
        $query="SELECT * from $this->tableName d
                inner join creditAccounts c on c.id_creditAccount=d.id_creditAccount
                where d.dis_date=\"$date\" and c.id_creditAccount=$accountId";
        try{
            $this->connection = Connection::getInstance();
            $results=$this->connection->execute($query);
        }
        catch (Exception $ex) {
            throw $ex;
        }
        if(!empty($results)){
            $row=$results[0];
            $creditAccount=new CreditAccount($row["id_creditAccount"],$row["company"]);
            return new Discount($row["id_discount"],$row["dis_perc"],$row["dis_date"],$creditAccount);
        }
        else{
            return new Discount("",0,$date,$accountId);
        }
        
        
        
    }
}
