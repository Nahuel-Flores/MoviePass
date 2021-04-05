<?php

namespace DAO;

use Models\Movie;
use Controllers\GenreController;


class MoviedbDAO{
    private $movies;
    private $totalPages;

    public function __construct() {     
        $this->movies=array();
    }

    //si no se aclara se usa region argentina
    public function getAll($query,$page=1,$language="es-AR",$region="US")  //puede ser popular , upcoming, top_rated o now_playing(esta es la de carteleras)
    {
        $filename="https://api.themoviedb.org/3/movie/$query?api_key=".MOVIEDB_KEY."&language=$language&page=$page&region=$region";
        return $this->jsonToArray($filename);
    }

    public function searchMovie($query,$page,$language="es-AR",$region="US"){ //talvez agregarle por año
        $query=str_replace(" ","%20",$query);
        $filename="https://api.themoviedb.org/3/search/movie?api_key=".MOVIEDB_KEY."&language=$language&query=$query&page=$page&include_adult=true";
        return $this->jsonToArray($filename);
    }

    private function jsonToArray($filename){
        $this->movies=array();
        $jsonResults=file_get_contents($filename);
        $array=($jsonResults)?json_decode($jsonResults,true):array();
        $this->setTotalPages($array["total_pages"]);
        $genreContr=new GenreController();
        foreach ($array["results"] as $movie) {               
            $newMovie=new Movie($movie["title"],
                                $movie["id"],
                                $movie["overview"],
                                $movie["poster_path"],
                                "", //esta busqueda no trae duracion
                                "", 
                                $genreContr->idArrayToObjects($movie["genre_ids"]),//arreglo de objetos genero
                                $movie["release_date"]);
            $this->movies[]=$newMovie;
        }
        return $this->movies;
    }

    public function getDetailsById($id, $language="es-AR"){
        $filename="https://api.themoviedb.org/3/movie/$id?api_key=".MOVIEDB_KEY."&language=$language";
        $genreContr=new GenreController();
        $this->movies=array();
        $jsonResults=file_get_contents($filename);
        $array=($jsonResults)?json_decode($jsonResults,true):array();
        $length=($array["runtime"]!=0)? $array["runtime"]: 120;
        $newMovie= new Movie($array["title"],
                            $id,
                            $array["overview"],
                            $array["poster_path"],
                            $this->getVideo($id),
                            $length,
                            $genreContr->genresArrayToObject($array["genres"]), // arreglo de objetos genero
                            $array["release_date"]);
        return $newMovie;
    }

    public function getVideo($idMovie,$language="es-AR"){
        $filename="https://api.themoviedb.org/3/movie/$idMovie/videos?api_key=".MOVIEDB_KEY."&language=$language";
        $jsonResults=file_get_contents($filename);
        $array=($jsonResults)?json_decode($jsonResults,true):array();
        if (!empty($array["results"])) {
            $obj=$array["results"][0];
            return $obj["key"];
        }
        else
            return "-";  
    }

    /**
     * Get the value of pages
     */ 
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * Set the value of pages
     *
     * @return  self
     */ 
    public function setTotalPages($pages)
    {
        $this->totalPages = $pages;

        return $this;
    }
}
?>