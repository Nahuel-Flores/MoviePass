<?php

namespace Controllers;

use Models\PaymentCC as PaymentCC;
use DAO\PaymentCCDAO as PaymentCCDAO;
use \Exception as Exception;

class PaymentCCController{
    private $paymentCCDao;

    public function __construct() {
        $this->paymentCCDao = new PaymentCCDAO();
    }

    public function add($id_purchase,$id_creditAccount,$aut_code,$date,$total){
        try{
            $this->paymentCCDao->add($id_purchase,$id_creditAccount,$aut_code,$date,$total);
        }
        catch(Exception $e){
            $message="Error payment not added.";
            include(VIEWS_PATH."message_view.php");
        }
    }

    public function getById($idPayment){
        try{
            return $this->paymentCCDao->getById($idPayment);
        }
        catch(Exception $e){
            $message="Error getting payment.";
            include(VIEWS_PATH."message_view.php");
        }
    }

    public function getAll(){
        try{
            return $this->paymentCCDao->getAll();
        }
        catch(Exception $e){
            $message="Error getting the payments.";
            include(VIEWS_PATH."message_view.php");
        }
    }
}

?>