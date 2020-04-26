<?php

namespace db;
require_once "lib/global.php";

use FilmRepositoryInterface;
use model\Film;
use model\Genre;

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

    /**
     * Creates a new film with data
     * from passed $film_data array.
     *
     * @param array $film_data Associative array which contains data used to
     *                          create a new film
     */
    public function add_film(array $film_data)
    {
        $film = new Film();

        $film->__set("Name", $film_data["title"]);
        $this->set_film_attributes($film, $film_data);
        $film->save();
    }

    /**
     * Returns all films or films starting with
     * the letter $first_letter.
     * If the $first_letter is not set, then all films are returned.
     * @param string|null $first_letter
     * @return array Containing films
     */
    public function get_movies(string $first_letter = null) : array
    {
        $f = new Film();
        $films = $f->loadAll(" WHERE films.Name LIKE " . " '$first_letter%'");

        $film_collection = [];
        if ($films === null){
            return $film_collection;
        }

        //for all films set attributes from model data
        foreach ($films as $film){
            $film->setId($film->getPrimaryKey());
            $film->setTitle($film->__get("Name"));
            $film->setDuration($film->__get("Duration"));
            $film->setYear($film->__get("Year"));
            $film->setGenre($this->get_genre_name($film->__get("GenreId")));

            $film_collection[] = $film;
        }

        return $film_collection;
    }

    /**
     * Returns the image data represented as a string
     * and the image type represented as a string
     * in an associative array with keys:
     * image_type and image_data.
     * @param int $id The primary key of the film.
     * @return array Array containing the image data and image type.
     */
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

    /**
     * Returns all genre names that exist in
     * the database.
     * @return array
     */
    public function get_genres()
    {
        $genre = new Genre();
        return $genre->loadAll();
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

    private function get_genre_name(int $genre_id){
        $genre = new Genre();
        return $genre->load($genre_id);
    }

    /**
     * Returns the primary key of the genre
     * with name $genre_name.
     * @param string $genre_name
     * @return mixed
     */
    public function get_genre_id(string $genre_name){
        $genre = new Genre();
        $col = $genre->loadAll(" WHERE Name = '$genre_name'");
        if (!empty($col)){
            return $col[0]->getPrimaryKey();
        }
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