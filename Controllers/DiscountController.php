<?php

namespace Controllers;
use DAO\DiscountDAO;
use \Exception as Exception;


class DiscountController{
    private $discountDao;
    
    public function __construct() {
        $this->discountDao = new DiscountDAO();
    }

    public function add($percent,$date,$creditAccountId)
    {
        try{
            $this->discountDao->add($percent,$date,$creditAccountId);
            $this->showModifyDiscounts();
        }
        catch(Exception $e){
            $message="Error adding discount.";
            include(VIEWS_PATH."message_view.php");
        }
        
    }

    public function showModifyDiscounts($date=""){
        try{
            if($date==""){
                $date=date("Y-m-d");
            }
            $discounts=$this->discountDao->getByDate($date);
        }
        catch(Exception $e){
            $message="Error getting discounts.";
            include(VIEWS_PATH."message_view.php");
        }
        include(VIEWS_PATH."discounts_view.php");
    }

    public function getByDate($date){
        try{
            return $this->discountDao->getByDate($date);
        }
        catch(Exception $e){
            return false;
        }
    }

    public function getByDateAccount($date,$accountId){
        try{
            return $this->discountDao->getByDateAccount($date,$accountId);
        }
        catch(Exception $e){
            return false;
        }
    }
}

?>