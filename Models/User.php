<?php
    namespace Models;

    use Models\UserProfile as UserProfile;
    use Models\UserRole as UserRole;

    class User 
    {
        private $id;
        private $email;
        private $password;
        private $userProfile; 
        private $userRole;


        public function __construct($id,$email,$password,$firstName,$lastName,$dni,$userType,$description){
            $this->id = $id;
            $this->email = $email;
            $this->password = $password;
            $this->userProfile = new UserProfile($firstName,$lastName,$dni); 
            $this->userRole = new UserRole($userType,$description); 
            
        }


        //-----------------Getters-----------------

        public function getId(){
            return $this->id;
        }

        public function getEmail(){
            return $this->email;
        }

        public function getPassword(){
            return $this->password;
        }

        public function getUserProfile(){
            return $this->userProfile;
        }

        public function getUserRole(){
            return $this->userRole;
        }

        //-----------------Setters-----------------
        
        public function setId($id){
            return $this->id = $id;
        }
        
        public function setEmail($email){
            return $this->email = $email;
        }

        public function setPassword($password){
            return $this->password = $password;
        }

        public function setUserProfile($userProfile){
            return $this->userProfile = $userProfile;
        }

        public function setUserRole($userRole){
            return $this->userRole = $userRole;
        }
    
    }
?>