<?php

namespace db;

use FilmRepositoryInterface;
use model\Film;


class FilmRepository implements FilmRepositoryInterface
{
    private static $instance = null;
    private $film_filename;
    private $file_handler;
    private $last_id;
    private $image_dir;

    private function __construct()
    {
        $this->file_handler = new FileHandler();
        $this->last_id = -1;
        $this->image_dir = "db/images/";
    }

    public static function get_instance(){
        if (self::$instance === null){
            self::$instance = new FilmRepository();
        }

        return self::$instance;
    }

    public function set_filename(string $filename){
        $this->film_filename = $filename;
    }

    /**
     * @inheritDoc
     */
    public function insert(string $data)
    {
        $this->file_handler->add_line($this->film_filename, $data);
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id)
    {
        $line_to_delete = $this->file_handler->get_line_starting_with($this->film_filename, '"'.strval($id).'"');
        $this->file_handler->delete_line($this->film_filename, $line_to_delete);
    }

    /**
     * @inheritDoc
     */
    public function select(...$id)
    {
        if (func_num_args() > 0){

            $id = func_get_args()[0];

            return $this->file_handler->get_line_starting_with($this->film_filename, '"'.strval($id).'"');

        } else {
            return $this->file_handler->get_lines($this->film_filename);
        }
    }

    public function get_movies(string $first_letter = null):array{
        $all_movies = $this->select();
        $films = [];

        foreach ($all_movies as $movie_line){
            if ($movie_line === ''){
                continue;
            }

            //get film attributes and remove double quotes
            $movie_line_parts = preg_split('~(?<!\\\)' . preg_quote(',', '~') . '~', $movie_line);
            $movie_line_parts = array_map(function(string $e){
                return substr($e, 1, strlen($e)-2);
            }, $movie_line_parts);

            if($first_letter === null){
                $films[] = $this->construct_film($movie_line_parts);
            }
            else if (strtoupper(substr($movie_line_parts[1], 0, 1))   === strtoupper($first_letter)){
                $films[] = $this->construct_film($movie_line_parts);
            }

        }

        return $films;
    }

    private function construct_film(array $film_line_parts):Film{
        $film = new Film();
        $film->setId(intval($film_line_parts[0]));
        $film->setTitle($film_line_parts[1]);
        $film->setGenre(intval($film_line_parts[2]));
        $film->setYear(intval($film_line_parts[3]));
        $film->setDuration(intval($film_line_parts[4]));
        $film->setHeadline($film_line_parts[5]);

        return $film;
    }


    private function set_id(Film $film){
        //assign ID
        $this->update_last_id();
        $this->last_id = $this->last_id+1;

        $film->setId($this->last_id);
    }

    public function add_film(array $film_data){

        $film = new Film();

        //assign id
        $this->set_id($film);

        //get genre id from genre.txt
        $genre_definition = $this->file_handler->get_lines_contain("db/genre.txt", $film_data['genre'])[0];
        $genre_id = explode(",", $genre_definition)[0];

        $film->set_attributes($film_data);

        //turn film_data elements to correct format
        $film_data = array_map(function ($element){
            return '"' . $element . '"';
        }, $film_data);

        //replace genre with genre_id
        $film_data['genre'] = $genre_id;

        //turn the film into a string
        $film_str = $film->__toString();

        //insert into database file
        $this->insert($film_str);
    }


    private function update_last_id(){
        $last_line = $this->file_handler->get_last_line($this->film_filename);
        if ($last_line === ''){
            $this->last_id = -1;
        }else{
            $last_line_id = explode(',', $last_line)[0];
            $last_line_id = substr($last_line, 1, strlen($last_line)-1);
            $this->last_id = intval($last_line_id);
        }

    }

    public function get_genres(): array
    {
        $genre_lines = $this->file_handler->get_lines("db/genre.txt");
        $genre_names = [];

        foreach ($genre_lines as $line){
            $genre_names[] = explode(',', $line)[1];
        }

        return $genre_names;
    }

    public function save_image(array $image_data)
    {
        $file = $image_data['name'];
        $path = pathinfo($file);
        $filename = $path['filename']; //ensures that no two students with the same original filename produce files with the same names
        $ext = $path['extension'];
        $temp_name = $image_data['tmp_name'];
        $path_filename_ext = $this->image_dir.$filename.".".$ext;
        move_uploaded_file($temp_name, $path_filename_ext);
    }

    public function get_image_url(int $id)
    {
        $movie_line = $this->select($id);
        $movie_line_parts = explode(',', $movie_line);
        $movie = $this->construct_film($movie_line_parts);


        return $this->image_dir.substr($movie->getHeadline(), 1, strlen($movie->getHeadline())-3);
    }

    public function get_image_data_and_type(int $id): array
    {
        // TODO: Implement get_image_data_and_type() method.
    }
}