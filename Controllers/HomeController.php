<?php 

    namespace Controllers;

    use Controllers\CinemaController;
    use Controllers\ProjectionController;

    
    class HomeController{

        public function showCinemasList(){
            $cinemaController=new CinemaController();
            $cinemaController->showCinemasList();
        }

        public function showMoviesList(){
            $projContr = new ProjectionController();
            $projContr->showProjectionsList();
        }

        public function logIn(){
            $userController = new userController();
            $userController->logIn();
        }

        public function signIn(){
            $userController = new userController();
            $userController->signIn();
        }

        public function logOut(){
            $userController = new userController();
            $userController->finishSession();
            $this->showHome();
        }

        public function showHome()
        {
            $projectionController = new ProjectionController (); 
            $projs = $projectionController->getAllProjections();
            include VIEWS_PATH."home_page.php";
        }

    }

?>