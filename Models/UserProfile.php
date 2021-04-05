<?php
    namespace Models;

    class UserProfile 
    {
        private $firstName;
        private $lastName;
        private $dni;

        public function __construct($firstName,$lastName,$dni){
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->dni = $dni;
        }

        //-----------------Getters-----------------

        public function getFirstName(){
            return $this->firstName;
        }

        public function getLastName(){
            return $this->lastName;
        }

        public function getName(){
            return $this->firstName." ".$this->lastName;
        }

        public function getDni(){
            return $this->dni;
        }


        //-----------------Setters-----------------
    
        public function setFirstName($firstName){
            return $this->firstName = $firstName;
        }

        public function setLastName($lastName){
            return $this->lastName = $lastName;
        }

        public function setDni($dni){
            return $this->dni = $dni;
        }
    
    }
    

?>