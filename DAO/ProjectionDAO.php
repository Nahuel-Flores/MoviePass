<?php

namespace DAO;

use Models\Projection;
use Models\Movie;
use DAO\GenreXMovieDAO;
use DAO\RoomDAO;
use DAO\CinemaDAO;
use DAO\Connection;
use \Exception as Exception;
use Models\Cinema;
use Models\Room;

class ProjectionDAO
{
    private $connection;
    private $genrexM;
    private $cinemaDao;
    private $roomDao;
    private $tableName = "projections";

    public function __construct()
    {
        $this->genrexM = new GenreXMovieDAO();
        $this->roomDao=new RoomDAO();
        $this->cinemaDao=new CinemaDAO();
    }

    public function add($roomId,$movieId,$date,$time)
    {
        $query = "INSERT INTO $this->tableName (id_proj,id_room,id_movie,proj_date,proj_time) 
                        VALUES (:id_proj,:id_room,:id_movie,:proj_date,:proj_time);";
        $parameters["id_proj"] = time();
        $parameters["id_room"] = $roomId;
        $parameters["id_movie"] = $movieId;
        $parameters["proj_date"] = $date;
        $parameters["proj_time"] = $time;
        try {
            $this->connection = Connection::getInstance();
            $this->connection->executeNonQuery($query, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function remove($id)
    {
        $query = "DELETE FROM $this->tableName 
                WHERE id_proj=$id";
        try {
            $this->connection = Connection::getInstance();
            return $this->connection->executeNonQuery($query);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * devuelve todo el array de funciones futuras de una sala 
     * 
     */
    public function getArrayByRoomId($roomId)
    {
        $query = "SELECT p.id_proj,p.proj_date,p.proj_time,p.id_room,m.id_movie,m.title,m.length,m.synopsis,m.poster_url,m.video_url,m.release_date 
                from $this->tableName p
                inner join movies m on m.id_movie=p.id_movie
                where p.id_room=$roomId and concat(p.proj_date,' ',p.proj_time) > now()
                order by(concat(p.proj_date,' ',p.proj_time))";
        try {
            $this->connection = Connection::getInstance();
            $results = $this->connection->execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }
        $projectionList = $this->resultsToProjsArray($results);
        return $projectionList;
    }


    public function getById($id)
    {
        $query = "SELECT p.id_proj,p.proj_date,p.proj_time,p.id_room,m.id_movie,m.title,m.length,m.synopsis,m.poster_url,m.video_url,m.release_date 
                from $this->tableName p
                inner join movies m on m.id_movie=p.id_movie
                where p.id_proj=$id";
        try {
            $this->connection = Connection::getInstance();
            $results = $this->connection->execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }
        $projection=$this->resultsToProjObj($results);
        return $projection;
    }


    /**
     * retorna todas las futuras proyecciones agrupadas por pelicula
     */
    public function getAllProjectionsGroup()
    {
        $query = "SELECT p.id_proj,p.proj_date,p.proj_time,p.id_room,m.id_movie,m.title,m.length,m.synopsis,m.poster_url,m.video_url,m.release_date 
                from $this->tableName p
                inner join movies m on m.id_movie=p.id_movie
                where concat(p.proj_date,' ',p.proj_time) > now()
                group by(m.id_movie)";
        try {
            $this->connection = Connection::getInstance();
            $results = $this->connection->execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }
        $projectionList = $this->resultsToProjsArray($results);
        return $projectionList;
    }

    /**
     * todas las futuras projs sin agrupar por pelicula
     */
    public function getAllProjections()
    {
        $query = "SELECT p.id_proj,p.proj_date,p.proj_time,p.id_room,m.id_movie,m.title,m.length,m.synopsis,m.poster_url,m.video_url,m.release_date 
                from $this->tableName p
                inner join movies m on m.id_movie=p.id_movie
                where concat(p.proj_date,' ',p.proj_time) > now()";
        try {
            $this->connection = Connection::getInstance();
            $results = $this->connection->execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }
        $projectionList = $this->resultsToProjsArray($results);
        return $projectionList;
    }

    /**
     * filtra por ciudad y/o fecha al mismo tiempo
     * @param array $params ["id_city"], y $params["proj_date"]
     */
    public function projectionFilters($params){
        $query="SELECT *  from $this->tableName p 
                inner join movies m on m.id_movie=p.id_movie
                inner join rooms r on r.id_room=p.id_room
                inner join cinemas c on r.id_cinema=c.id_cinema
                where concat(p.proj_date,' ',p.proj_time) > now()";
        $filteredParams=array_filter($params);
        foreach($filteredParams as $key => $value){
            $query=$query." and $key=\"$value\"";
        }
        $query=$query." group by(m.id_movie)";
        try {
            $this->connection = Connection::getInstance();
            $results = $this->connection->execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }
        $projectionsList = array();
        foreach ($results as $row) {
            $movie = new Movie(
                $row["title"],
                $row["id_movie"],
                $row["synopsis"],
                $row["poster_url"],
                $row["video_url"],
                $row["length"],
                [],
                $row["release_date"]
            );
            $movie->setGenres($this->genrexM->getByMovieId($row["id_movie"]));
            $cinema=new Cinema($row["name_cinema"],$row["id_cinema"],$row["id_province"],$row["id_city"],$row["address"]);
            $room = new Room($row["id_room"], $row["capacity"], $row["ticket_price"], $row["descript"], $cinema);
            $proj = new Projection($row["id_proj"], $movie, $row["proj_date"], $row["proj_time"], $room);
            $projectionsList[] = $proj;
        }
        return $projectionsList;
    }


    /**
     * solo las activas 
     */
    public function getAllProjectionsByUser($usrId){
        $query="SELECT * from $this->tableName pr
                inner join purchases p on p.id_user=$usrId
                inner join tickets t on t.id_purchase=p.id_purchase
                inner join movies m on pr.id_movie=m.id_movie
                inner join rooms r on r.id_room=pr.id_room
                where pr.id_proj=t.id_proj and concat(pr.proj_date,' ',pr.proj_time) > now()";
        try {
            $this->connection = Connection::getInstance();
            $results = $this->connection->execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }
        $projectionList = array();
        foreach ($results as $row) {
            $movie = new Movie($row["title"],$row["id_movie"],$row["synopsis"],$row["poster_url"],$row["video_url"],$row["length"],[],$row["release_date"]);
            $room=new Room($row["id_room"], $row["capacity"], $row["ticket_price"], $row["descript"],"");
            $projectionList[] = new Projection($row["id_proj"], $movie, $row["proj_date"], $row["proj_time"], $room);
        }
        return $projectionList;
    }

    /**
     * devuelve las futuras proyecciones de una ciudad determinada
     */
    public function getAllProjectionsByCity($cityId)
    {
        $query = "SELECT p.id_proj,p.proj_date,p.proj_time,r.id_room,r.capacity,r.ticket_price,r.descript,c.id_cinema,m.title,m.id_movie,m.synopsis,m.poster_url,m.video_url,m.length,m.release_date 
                from $this->tableName p
                inner join movies m on m.id_movie=p.id_movie
                inner join rooms r on r.id_room=p.id_room
                inner join cinemas c on r.id_cinema=c.id_cinema
                where c.id_city=$cityId and concat(p.proj_date,' ',p.proj_time) > now()";
        try {
            $this->connection = Connection::getInstance();
            $results = $this->connection->execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }
        $projectionList = array();
        foreach ($results as $row) {
            $movie = new Movie(
                $row["title"],
                $row["id_movie"],
                $row["synopsis"],
                $row["poster_url"],
                $row["video_url"],
                $row["length"],
                [],
                $row["release_date"]
            );
            $movie->setGenres($this->genrexM->getByMovieId($row["id_movie"]));
            $cinema=$this->cinemaDao->getById($row["id_cinema"]);
            $room = new Room($row["id_room"], $row["capacity"], $row["ticket_price"], $row["descript"],$cinema);
            $proj = new Projection($row["id_proj"], $movie, $row["proj_date"], $row["proj_time"], $room);
            $projectionList[] = $proj;
        }
        return $projectionList;
    }

    public function getAllProjectionsByDate($date)
    {
        $query = "SELECT p.id_proj,p.proj_date,p.proj_time,p.id_room,m.id_movie,m.title,m.length,m.synopsis,m.poster_url,m.video_url,m.release_date 
                    from $this->tableName p
                    inner join movies m on p.id_movie=m.id_movie
                    where p.proj_date = \"$date\" and concat(p.proj_date,' ',p.proj_time) > now()";
        try {
            $this->connection = Connection::getInstance();
            $results = $this->connection->execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }
        $projectionList = $this->resultsToProjsArray($results);
        return $projectionList;
    }

    public function getByDateRoom($date,$roomId){
        $query="SELECT p.id_proj,p.proj_date,p.proj_time,p.id_room,m.id_movie,m.title,m.length,m.synopsis,m.poster_url,m.video_url,m.release_date 
                from $this->tableName p
                inner join movies m on p.id_movie=m.id_movie
                where p.proj_date = \"$date\" and p.id_room=$roomId";
        try {
            $this->connection = Connection::getInstance();
            $results = $this->connection->execute($query);
        } catch (Exception $ex) {
            throw $ex;
        }
        $projectionList = $this->resultsToProjsArray($results);
        return $projectionList;
    }

    /**
     * retorna la cantidad de funciones de una pelicula existen en una fecha determinada
     */
    public function existByDate($date,$movieId,$roomId){
        $query="SELECT COUNT(*) as count  from $this->tableName p 
            inner join movies m on m.id_movie=p.id_movie
            where p.proj_date=\"$date\" and m.id_movie=$movieId and p.id_room<>$roomId";
        try{
            $this->connection = Connection::getInstance();
            $results = $this->connection->execute($query);
        }catch (Exception $ex) {
            throw $ex;
        }
        return $results[0]["count"];
    }

    private function resultsToProjsArray($results){
        $projectionList = array();
        foreach ($results as $row) {
            $movie = new Movie($row["title"], $row["id_movie"], $row["synopsis"], $row["poster_url"], $row["video_url"], $row["length"], [], $row["release_date"]);
            $movie->setGenres($this->genrexM->getByMovieId($row["id_movie"]));
            $room = $this->roomDao->getById($row["id_room"]);
            $projectionList[] = new Projection($row["id_proj"], $movie, $row["proj_date"], $row["proj_time"], $room);
        }
        return $projectionList;
    }

    private function resultsToProjObj($results){
        $row = $results[0];
        $movie = new Movie($row["title"], $row["id_movie"], $row["synopsis"], $row["poster_url"], $row["video_url"], $row["length"], [], $row["release_date"]);
        $movie->setGenres($this->genrexM->getByMovieId($row["id_movie"]));
        $room = $this->roomDao->getById($row["id_room"]);
        $projection = new Projection($row["id_proj"], $movie, $row["proj_date"], $row["proj_time"], $room);
        return $projection;
    }


}
?>