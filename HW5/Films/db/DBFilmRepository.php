<?php

namespace db;
require_once "lib/global.php";

use FilmRepositoryInterface;
use model\Film;

class DBFilmRepository implements FilmRepositoryInterface
{
    private $pdo;
    private static $instance = null;

    private function __construct()
    {
        $this->pdo = DBPool::getInstance();
    }

    public static function getInstance(){
        if (self::$instance === null){
            self::$instance = new DBFilmRepository();
        }
        return self::$instance;
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id)
    {
        $film = new Film();
        $film->load($id);

        $film->delete();
    }

    public function select(...$id)
    {

        $id = $id[0];
        $sql = <<< 'SQL'
        SELECT * FROM films WHERE FilmId = ?
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id ?? 0]);

        return $stmt->fetch();
    }

    public function add_film(array $film_data)
    {
        $film = new Film();

        $film->__set("Name", $film_data["title"]);
        $this->set_film_attributes($film, $film_data);
        $film->save();
    }

    public function get_movies(string $first_letter = null)
    {
        $movie_list = [];
        $sql = "SELECT films.*, genre.Name as GenreName 
                FROM films
                JOIN genre
                ON genre.GenreId = films.GenreId";

        if($first_letter !== null){
            $sql .= " WHERE films.Name Like :term ";
            $term = $first_letter."%";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':term'=> $term]);
        } else {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
        }


        $stdObjects = $stmt->fetchAll();

        //construct movies and add them to a list
        foreach($stdObjects as $stdObj){

            $film = new Film();
            $film->set_attributes(["title"=>$stdObj->Name, "year"=>$stdObj->Year, "duration"=>$stdObj->Duration,
                "headline_data"=>$stdObj->Cover, "id"=>$stdObj->FilmId, "genre"=>$stdObj->GenreName, "headline"=>$stdObj->Cover]);

            $movie_list[] = $film;
        }

        return $movie_list;
    }

    public function get_image_data_and_type($id): array{
        $sql = <<< 'SQL'
        SELECT Cover, image_type From films WHERE FilmId = ?
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id ?? 0]);
        $obj = $stmt->fetch();

        $data = ['image_data'=>$obj->Cover, 'image_type'=>$obj->image_type];
        return $data;
    }

    public function get_genres()
    {
        $sql = <<< 'SQL'
        SELECT Name FROM genre
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $objs = $stmt->fetchAll();
        $genres = [];
        foreach($objs as $obj){
            $genres[] = $obj->Name;
        }

        return $genres;
    }

    /**
     * Sets attributes that correspond to database columns for the Film.
     * @param Film $film
     * @param array $film_data
     */
    private function set_film_attributes(Film $film, array $film_data){
        $film->__set("Name",$film_data["title"]);
        $film->__set("GenreId", $this->get_genre_id($film_data["genre"]));
        $film->__set("Duration", $film_data["duration"]);
        $film->__set("Year",$film_data["year"]);
        $film->__set("Cover", $film_data["image_data"]);
        $film->__set("image_type", $film_data["image_type"]);

    }

    public function get_genre_id(string $genre_name){
        $sql = <<< 'SQL'
        SELECT GenreId FROM genre WHERE Name = ?
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$genre_name]);

        return $stmt->fetch()->GenreId;
    }

    public function save_image(array $image_data)
    {
        // TODO: Implement save_image() method.
    }

    public function get_image_url(int $id)
    {
        // TODO: Implement get_image_url() method.
    }
}