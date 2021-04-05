<?php
    namespace Controllers;

    use Models\User as User;
    use Models\UserProfile as UserProfile;
    use Models\UserRole as UserRole;
    use DAO\UserDAO as UserDAO;
    use Controllers\HomeController as HomeController;
    use Controllers\CinemaController as CinemaController;
    use \Exception as Exception;

    class UserController{
        private $userDao;

        public function __construct() {
            $this->userDao = new UserDao();
        }
   
    

        public function logIn($message = ""){
            include VIEWS_PATH."logIn.php";
        }
        
        public function signIn($message = ""){
            include VIEWS_PATH."signIn.php";
        }

        public function signInCinemaOwner($message = ""){
            include VIEWS_PATH."signIn_cinemaOwner.php";
        }


        private function createClient($email,$password,$firstName,$lastName,$dni,$userType){
            $id=(string)time();
            $client = new User($id,$email,$password,$firstName,$lastName,$dni,$userType,"Client");
            
            try{
                $this->userDao->add($client);
            }
            catch (Exception $ex) {
                throw $ex;
            }

            return $client;
        }

        private function createCinemaOwner($email,$password,$firstName,$lastName,$dni,$userType){
            $id=(string)time();
            $cinemaOwner = new User($id,$email,$password,$firstName,$lastName,$dni,$userType,"Cinema Owner");
            
            try{
                $this->userDao->add($cinemaOwner);
            }
            catch (Exception $ex) {
                throw $ex;
            }
            

            return $cinemaOwner;
        }

        public function verifySignIn($email,$password,$firstName,$lastName,$dni,$userType){            
            $user = null;
                  
            if($userType == 2){
                try{
                    $user = $this->createCinemaOwner($email,$password,$firstName,$lastName,$dni,$userType);
                }catch (Exception $ex) {
                    $message = "This email is already used";
                    $this->signInCinemaOwner($message);    
                }
                $this->startSession($user);
        
            }else{
                try{
                    $user = $this->createClient($email,$password,$firstName,$lastName,$dni,$userType);
                }catch (Exception $ex) {
                    $message = "This email is already used";
                    $this->signIn($message);
                }

                $this->startSession($user);
                
            }
            $homeController = new HomeController();
            $homeController->showHome();

        }

        public function verifyLogIn($email,$password){
            $user = null;
            try{
                $user = $this->userDao->findUser($email,$password);
                if ($user == null) {
                    $message = "The username or password is incorrect.Try again";
                    $this->logIn($message);
                } else {
                    $this->startSession($user);
                    $homeController = new HomeController();
                    $homeController->showHome();
                }   
            }
            catch (Exception $ex) {
                $message = "An unknown error has occurred";
                $this->logIn($message);
            }
        }

        private function startSession($user){
            $this->finishSession();
            session_start();
            $_SESSION['Id'] = $user->getId();
            $_SESSION['name'] = $user->getUserProfile()->getName();
            $_SESSION['userType'] = $user->getUserRole()->getUserType();
            
        }

        public function finishSession(){
            if(session_status () != 2){
                session_start();  
              }
            session_destroy();
        }

        public function getById(){
           $id_user = $_SESSION['Id'];
           
           try {
                $user = $this->userDao->getById($id_user);
           } catch (Exception $th) {
                $message="Error user not found.";
           }
           
           return $user;
        }

    }

?>