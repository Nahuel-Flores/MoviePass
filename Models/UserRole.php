<?php
    namespace Models;

    class UserRole
    {
        private $userType; // 1 - Client / 2 - Cinema owner / 3 - Admin 
        private $description;

        public function __construct($userType,$description){
            $this->userType = $userType;
            $this->description = $description;
        }


        //-----------------Getters-----------------

        public function getUserType(){
            return $this->userType;
        }

        public function getDescription(){
            return $this->description;
        }

        //-----------------Setters-----------------

        public function setUserType($userType){
            return $this->userType = $userType;
        }

        public function setDescription($description){
            return $this->description = $description;
        }

    }
    



?>