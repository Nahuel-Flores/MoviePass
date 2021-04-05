<?php
    namespace Models;

    class PaymentCC 
    {
        private $id;
        private $aut_code;
	    private $date;
	    private $total;

        public function __construct($id,$aut_code,$date,$total){
            $this->id = $id;
            $this->aut_code = $aut_code;
            $this->date = $date;
            $this->total = $total;
        }

        //-----------------Getters-----------------

        public function getId(){
            return $this->id;
        }

        public function getAut_code(){
            return $this->aut_code;
        }

        public function getDate(){
            return $this->date;
        }

        public function getTotal(){
            return $this->total;
        }

        
        //-----------------Setters-----------------
    
        public function setId($id){
            return $this->id = $id;
        }

        public function setAut_code($aut_code){
            return $this->aut_code = $aut_code;
        }

        public function setDate($date){
            return $this->date = $date;
        }

        public function setTotal($total){
            return $this->total = $total;
        }
    
    }
    

?>