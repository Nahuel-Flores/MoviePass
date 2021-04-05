<?php

namespace DAO;

use Models\Genre;
use \Exception as Exception;

class GenreDAO
{
    private $connection;
    private $tableName = "genres";

    public function add($genre)
    {
        $query = "INSERT INTO $this->tableName (id_genre,genre_name) VALUES (:id_genre,:genre_name)";
        $parameters["id_genre"] = $genre->getId();
        $parameters["genre_name"] = $genre->getName();
        try {
            $this->connection = Connection::getInstance();
            $this->connection->executeNonQuery($query, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }


    public function getAll()
    {
        $query = "SELECT * from $this->tableName";
        try {
            $this->connection = Connection::getInstance();
            $results = $this->connection->execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }
        $genresList = array();
        foreach ($results as $row) {
            $newGenre = new Genre($row["id_genre"], $row["genre_name"]);
            $genresList[] = $newGenre;
        }
        return $genresList;
    }

    public function getById($id)
    {
        $query = "SELECT * from genres where id_genre=$id";
        try {
            $this->connection = Connection::getInstance();
            $results = $this->connection->execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }
        $row = $results[0];
        $genre = new Genre($row["id_genre"], $row["genre_name"]);
        return $genre;
    }
}
