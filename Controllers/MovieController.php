<?php

namespace Controllers;
use DAO\MovieDAO;
use \Exception as Exception;

class MovieController{
    private $movieDao;

    public function __construct() {
        $this->movieDao = new MovieDAO();
    }

    public function getAll(){
        try{
            return $this->movieDao->getAll();
        }
        catch(Exception $e){
            $message="No movies could be found.";
            include(VIEWS_PATH."message_view.php");
        }
    }

    public function getById($id){
        try{
            return $this->movieDao->getById($id);
        }
        catch(Exception $e){
            $message="No movies could be found.";
            include(VIEWS_PATH."message_view.php");
        }
    }
    
    public function filterByGenre($genresArray,$movies){ 
        $newArray=array();
        foreach ($movies as $movie) {
            $jaja=0;
            $genresMovie=$movie->getGenres();
            foreach ($genresMovie as $genM) {
                foreach ($genresArray as $strGen) {
                    if ($strGen ==$genM->getName()){
                        $jaja++;
                    }
                }
            }     
            if ($jaja==count($genresArray)) {
                $newArray[]=$movie;
            }
        }
        return $newArray;
    }

    public function searchByName($name){
        $movies=$this->getAll();
        $arrayFinded = array();
        foreach ($movies as $value) {
            if (stripos($value->getTitle(),$name)!==false)
            {
                array_push($arrayFinded,$value);
            }
        }
        return $arrayFinded; 
    }

    public function updateNowPlaying(){
        try{
            $this->movieDao->updateNowPlaying();
        }
        catch(Exception $e){
            $message="Error updating database.";
            include(VIEWS_PATH."message_view.php");
        }
    }

}
?>