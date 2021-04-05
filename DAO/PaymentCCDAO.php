<?php 
    namespace DAO;

    use Models\PaymentCC as PaymentCC;
    use \Exception as Exception;
    use DAO\Connection;

    
    class PaymentCCDAO
    {
        private $connection;
        private $tableName;

        public function __construct() {
            $this->tableName = "paymentCC";
        }

        public function add($id_purchase,$id_creditAccount,$aut_code,$date,$total){
            $query="INSERT INTO $this->tableName (id_purchase,id_creditAccount,aut_cod,paymentCC_date,total) VALUES(:id_purchase,:id_creditAccount,:aut_cod,:paymentCC_date,:total)";
            $params["id_purchase"]=$id_purchase;
            $params["id_creditAccount"]=$id_creditAccount;
            $params["aut_cod"]=$aut_code;
            $params["paymentCC_date"]=$date;
            $params["total"]=$total;
            try {
                $this->connection = Connection::getInstance();
                $this->connection->executeNonQuery($query,$params);
            } catch (Exception $ex) {
                throw $ex;
            }
        }
        

        public function getById($id)
        {
            $query = "SELECT * FROM " . $this->tableName . " where id=$id";
            try {
                $this->connection = Connection::GetInstance();
                $resultSet = $this->connection->Execute($query);
            } catch (Exception $ex) {
                throw $ex;
            }
            $row = $resultSet[0];
            $paymentCC = new PaymentCC($id, $row["id_creditAccount"],$row["aut_cod"],$row["paymentCC_date"],$row["total"]);
            return $paymentCC;
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
            $payments = array();

            foreach ($resultSet as $row) {
                $paymentCC = new PaymentCC($row["id_paymentCC"], $row["id_creditAccount"],$row["aut_cod"],$row["paymentCC_date"],$row["total"]);
                array_push($payments, $paymentCC);
            }
            return $payments;
        }

        
    }
    
?>

