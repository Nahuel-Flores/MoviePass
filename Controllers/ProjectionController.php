<?php

namespace Controllers;

use DAO\ProjectionDAO;
use Controllers\MovieController;
use Controllers\GenreController;
use Controllers\TicketController;
use DateTime;
use \Exception as Exception;


class ProjectionController
{
    private $projDao;
    private $movieContr;

    public function __construct()
    {
        $this->projDao = new ProjectionDAO();
        $this->movieContr = new MovieController();
    }

    /**
     * este es cartelera jeje
     */
    public function showProjectionsList(){
        $locContro=new LocationController();
        $gencontr=new GenreController();
        try{
            $projectionList=$this->projDao->getAllProjectionsGroup();
            $genres=$gencontr->getAll();
            $provinces=$locContro->getAllProvinces();
            $initCities=$locContro->getCitiesByProvince(1);
            include(VIEWS_PATH."movies_list.php");
        }
        catch(Exception $e){
            $message="Projections are not currently available.";
            include(VIEWS_PATH."message_view.php");
        }
    }

    public function projectionFilters($cityId,$date,$genresJsonArray){
        $params["id_city"]=$cityId;
        $params["proj_date"]=$date;
        $projectionList=array();
        try{
            $projectionList=$this->projDao->projectionFilters($params);
            $projectionList=$this->filterByGenre(json_decode($genresJsonArray),$projectionList);
        }
        catch(Exception $e){
            $message="Error applying filters.";//TODO
        }
        finally{
            echo json_encode($projectionList,1);
        }
    }

    public function showProjectionSearch($search){
        $projectionList=$this->searchByName($search);
        echo json_encode($projectionList,1);
    }

    /*---------------------------------*/

    public function showProjections($roomId){
        $projs=$this->getArrayByRoomId($roomId);
        $ticketController=new TicketController();
        foreach ($projs as $proj) {
            $soldTickets = $ticketController->getSoldTicketsByProjId($proj->GetId());
            $capacity = $proj->getRoom()->getCapacity();
            $availableTickets = $capacity - $soldTickets;
            $availableArr[]=$availableTickets;
            $soldArr[]=$soldTickets;
        }
        include(VIEWS_PATH."projection_admin.php");
    }

    public function add($roomId,$movieId,$date,$time)
    {
        try{
            $this->projDao->add($roomId,$movieId,$date,$time);
        }
        catch(Exception $e){
            $message="Error adding the projection.";
        }
        finally{
            $this->showProjections($roomId);
        }
    }

    public function addFromList($roomId){
        try{
            $movieList=$this->movieContr->getAll();
            $gencontr=new GenreController();
            $genres=$gencontr->getAll();
        }
        catch(Exception $e){
            $message="No movies could be found.";
            include(VIEWS_PATH."message_view.php");
        }
        include(VIEWS_PATH."add_projection.php");
    }

    public function addFromListFiltered($genresJsonArray){
        $movies=$this->movieContr->getAll();    
        $movies=$this->movieContr->filterByGenre(json_decode($genresJsonArray),$movies);
        echo json_encode($movies,1);
    }

    public function addFromListSearch($search){
        try{
            $movieList=$this->movieContr->searchByName($search);
        }
        catch(Exception $e){
            $message="No movies could be found.";
            include(VIEWS_PATH."message_view.php");
        }
        echo json_encode($movieList,1);
    }

    public function updateMoviesList($roomId){
        $this->movieContr->updateNowPlaying();
        $this->addFromList($roomId);
    }

    /**
     * retorna todas las peliculas que tengan una funcion activa en el futuro sin repetirse.(cartelera hehe)
     */
    public function getAllProjectionsGroup()
    {
        try{
            return $this->projDao->getAllProjectionsGroup();
        }
        catch(Exception $e){
            $message="Error getting the projections.";
            include(VIEWS_PATH."message_view.php");
        }
    }

    /**
     * retorna todas las peliculas que tengan una funcion activa en el futuro sin agrupar por pelicula
     */
    public function getAllProjections()
    {
        try{
            return $this->projDao->getAllProjections();
        }
        catch(Exception $e){
            $message="Error getting the projections.";
            include(VIEWS_PATH."message_view.php");
        }
    }

    public function getById($id)
    {
        try{
            return $this->projDao->getById($id);
        }
        catch(Exception $e){
            $message="Error getting the projection.";
            include(VIEWS_PATH."message_view.php");
        }
    }


    /**
     * busca y devuelve array de proyecciones
     */
    public function searchByName($name){
        $projections=$this->getAllProjectionsGroup();
        $arrayFinded = array();
        foreach ($projections as $value) {
            $movie=$value->getMovie();
            if (stripos($movie->getTitle(),$name)!==false)
            {
                array_push($arrayFinded,$value);
            }
        }
        return $arrayFinded; 
    }

    /**
     * todo
     * filtro de generos para cartelera 
     */
    public function filterByGenre($genresArray,$projectionList)
    {
        $newArray = array();
        foreach ($projectionList as $proj) {
            $jaja = 0;
            $movie=$proj->getMovie();
            $genresMovie = $movie->getGenres();
            foreach ($genresMovie as $genM) {
                foreach ($genresArray as $strGen) {
                    if ($strGen == $genM->getName()) {
                        $jaja++;
                    }
                }
            }
            if ($jaja == count($genresArray)) {
                $newArray[] = $proj;
            }
        }
        return $newArray;
    }

    /**
     * devuelve todo el array de funciones futuras de una sala
     */
    public function getArrayByRoomId($id)
    {
        try{
            return $this->projDao->getArrayByRoomId($id);
        }
        catch(Exception $e){
            return array();
        }
    }

    public function getAllProjectionsByUser($usrId){
        try{
            return $this->projDao->getAllProjectionsByUser($usrId);
        }
        catch(Exception $e){
            return array();
        }
    }

    public function remove($id,$roomId)
    {
        try{
            $this->projDao->remove($id);
        }
        catch(Exception $e){
            $message="Error removing the projection.";
        }
        finally{
            $this->showProjections($roomId);
        }
    }

    public function getByDateRoom($date,$roomId){
        try{
            return $this->projDao->getByDateRoom($date,$roomId);
        }
        catch(Exception $e){
            $message="Error searching projection.";
            include(VIEWS_PATH."message_view.php");
        }
    }

    /**
     * valida si se respetan los 15min antes de cada funcion y no existe otra funcion de la pelicula en otro sala u cine el mismo dia
     */
    public function validateProjection($roomId,$movieId,$date,$time){
        if($this->projDao->existByDate($date,$movieId,$roomId)==0){         //si la pelicula no esta registrada en una funcion en ninguna otra sala de ningun otro cine el mismo dia
            $newMovie=$this->movieContr->getById($movieId);
            $projList=$this->getByDateRoom($date,$roomId);
            $initTime=new DateTime($date." ".$time);         //hora de inicio de funcion a crear
            $endTime=new DateTime($date." ".$time); 
            $endTime=$endTime->modify("+".$newMovie->getLength()." minutes");  //hora de finalizacion
            foreach ($projList as $value) {
                $datetime=$value->getDate()." ".$value->getHour();
                $initTime2=new DateTime($datetime);  
                $endTime2=new DateTime($datetime);               //hora de inicio de las funciones existentes
                $endTime2=$initTime2->modify("+".$value->getMovie()->getLength()." minutes");       //hora de fin
                if(($initTime<=$endTime2->modify("+15 minutes")) && ($endTime>=$initTime2->modify("-15 minutes"))){
                    $msg["msg"]="This time is not available";
                } 
            }
            if (!isset($msg["msg"])) {
                $msg["msg"]="Ok"; //si se puede insertar la nueva
            }
        }
        else {
            $msg["msg"]="This movie is already in another room or cinema";
        }
        echo json_encode($msg,1);
    }

    public function getAllProjectionsByCity($cityId){
        try{
            return $this->projDao->getAllProjectionsByCity($cityId);
        }
        catch(Exception $e){
            $message="Error getting the projections.";
            include(VIEWS_PATH."message_view.php");
        }
    }

    public function selectProjection($cityId,$movieId){
        $ticketContr=new TicketController();
        if ($cityId!="") {
            $cityProjs=$this->getAllProjectionsByCity($cityId);
        }
        else{
            $cityProjs=$this->getAllProjections();
        }
        $optionsArray=array();
        foreach ($cityProjs as $value) {
            if($value->getMovie()->getId()==$movieId){
                $optionsArray[]=$value;
            }
        }
        include(VIEWS_PATH."select_projection.php");
    }
}