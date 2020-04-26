<?php

namespace controller;


use view\ErrorView;
use view\FilmTableView;
use view\FormView;
use view\HeaderView;

class AddController extends AbstractController {

    private $errorMessage;
    private $error_set;
    private $form_data;
    private $film_repository;
    private $film_form_parameters;
    private $missing_params;
    private $film_data_valid;

    public function __construct($data = null, \FilmRepositoryInterface $film_repository) {;
        $this->form_data = $data;
        $this->film_repository = $film_repository;
        $this->errorMessage='';
        $this->film_form_parameters = ['title', 'year', 'duration', 'genre'];
        $this->missing_params = [];
        $this->film_data_valid = true;
        $this->error_set = false;
    }

    protected function doJob() {
        if ($this->form_data == null){
            $this->show_html(false);
        }else{
            $this->processData($this->form_data);
        }
    }

    /*
     * Validates data, checks for missing parameters and sends
     * the verified data to the repository to save.
     */
    public function processData(array $data) {

        //check for missing parameters
        $this->check_parameters($data);
        if(empty($this->missing_params) || !isset($_FILES['headline'])){

            //Validate data and set flag
            $this->validate_data($data);

            if ($this->film_data_valid && isset($_FILES['headline'])){
                $prepared_data = $this->prepare_film_data($data);
                $this->film_repository->add_film($prepared_data);
            } else {
                $this->errorMessage = 'Invalid parameters.';
                $this->error_set = true;
            }
        } else {
            $this->errorMessage .= "Missing parameters. ";
            foreach ($this->missing_params as $missing_param) {
                $this->errorMessage .= $missing_param;
            }
            $this->error_set = true;
        }

        // Show view
        $this->show_html($this->error_set);
    }

    /**
     * Checks if any of the parameters in the form are missing.
     * Return true if all parameters are present.
     * Return false if any are missing and set $missing_params array.
     * @param array $form_data
     * @return bool
     */
    private function check_parameters(array $form_data): bool{
        $this->missing_params = array_diff($this->film_form_parameters, array_keys($form_data));
        return empty($this->missing_params);
    }

    /**
     * Adds all data necessary for the persistence layer to
     * successfully save the film.
     * @param array $film_data
     * @return array
     */
    private function prepare_film_data(array $film_data):array{
        //add backslash to any comma added in the title and replace old value
        $title = $film_data['title'];
        $title = str_replace(',', "\,", $title);
        $film_data['title'] = $title;

        //save image and add image url to data
        $img_data = file_get_contents($_FILES['headline']['tmp_name']);
        $image_url = $_FILES['headline']['name'];
        $film_data['headline'] = $image_url;
        $film_data['image_data'] = $img_data;

        //save image type to data as well
        $image_type = explode('/',mime_content_type($_FILES['headline']['tmp_name']))[1];
        $film_data['image_type'] = $image_type;

        return $film_data;
    }

    /**
     * Returns an array of genre objects with
     * attributes set so that the views can
     * interact with them.
     * @return array Containing Genre objects
     */
    private function get_genres(): array{
        //get genres for the form view - this returns the Genre model object
        //we need to populate it with correct attribute values
        //so that the views can interact with it without them knowing
        //anything about the database model
        $genres = $this->film_repository->get_genres();
        $genres_with_attrs = [];
        foreach($genres as $genre){
            $genre->setName($genre->__get("Name"));
            $genre->setId($genre->getPrimaryKey());

            $genres_with_attrs[] = $genre;
        }

        return $genres_with_attrs;
    }

    private function show_html(bool $error = false){

        $header_view = new HeaderView("Add movies", true);
        $form_view = new FormView($this->get_genres());
        $error_view = new ErrorView($this->errorMessage);
        $movie_table = new FilmTableView($this->film_repository->get_movies());

        $header_view->generateHTML();
        $form_view->generateHTML();
        if($error){
            $error_view->generateHTML();
        }
        $movie_table->generateHTML();
    }

    /**
     * Validates data using regex.
     * Returns array containing keys that are parameter names
     * and values 0 or 1.
     * Value is 1 if the parameter is valid, and 0 if it is not.
     * Also sets the $film_data_valid flag to the appropriate value.
     * @param array $data
     * @return array
     */
    private function validate_data(array $data): array{
        $data_valid = [];

        //check year
        $data_valid['year'] = preg_match('/^\d{4}$/', $data['year']);

        //check if genre is only letters
        $data_valid['genre'] = preg_match('/^[a-zA-Z]{3,30}$/',  $data['genre']);

        //check if duration is only digits
        $data_valid['duration'] = preg_match('/^\d{1,4}/', $data['duration']);

        if (in_array(0, $data_valid)){
            $this->film_data_valid = false;
        }

        return $data_valid;
    }

}
