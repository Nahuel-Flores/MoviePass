<?php

namespace DAO;

use DAO\GenreDAO;
use \Exception as Exception;

class GenreXMovieDAO
{
    private $connection;
    private $tableName = "genres_x_movies";


    public function add($id_genre, $id_movie)
    {
        $query = "INSERT into $this->tableName(id_genre,id_movie) values(:id_genre,:id_movie)";
        $parameters["id_genre"] = $id_genre;
        $parameters["id_movie"] = $id_movie;
        try {
            $this->connection = Connection::getInstance();
            $this->connection->executeNonQuery($query, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function addGenresArray($arr, $idMovie)
    {
        foreach ($arr as $value) {
            $this->add($value->getId(), $idMovie);
        }
    }


    public function getByMovieId($movieId)
    {
        $query = "SELECT * from genres_x_movies where id_movie=$movieId";
        try {
            $this->connection = Connection::getInstance();
            $results = $this->connection->execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }
        $genreDAO = new GenreDAO();
        $genresArray = array();
        foreach ($results as $gen) {
            $genresArray[] = $genreDAO->getById($gen["id_genre"]);
        }
        return $genresArray;
    }
}
