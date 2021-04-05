<?php
    namespace Models;
    
    class CreditAccount 
    {
        private $id;
        private $company;

        public function __construct($id,$company) {
            $this->id = $id;
            $this->company = $company;
        }

        //-----------------Getters-----------------

        public function getId(){
            return $this->id;
        }

        public function getCompany(){
            return $this->company;
        }


        //-----------------Setters-----------------
    
        public function setId($id){
            return $this->id = $id;
        }

        public function setCompany($company){
            return $this->company = $company;
        }

    }
    
?>