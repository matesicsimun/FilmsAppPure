<?php

namespace controller;


use view\ErrorView;
use view\FilmTableView;
use view\FormView;
use view\HeaderView;

class AddController extends AbstractController {

    private $errorMessage;
    private $form_data;
    private $film_repository;

    public function __construct($data = null, \FilmRepositoryInterface $film_repository) {;
        $this->form_data = $data;
        $this->film_repository = $film_repository;
        $this->errorMessage='';
    }

    protected function doJob() {
        if ($this->form_data == null){
            $this->show_html(false);
        }else{
            $this->processData($this->form_data);
        }
    }

    public function processData(array $data) {

        //check for missing parameters
        $expected_params = ['title', 'year', 'duration', 'genre'];
        $missing_params = array_diff($expected_params, array_keys($data));

        if(empty($missing_params) || !isset($_FILES['headline'])){

            //validate data
            $valid = True;
            $data_valid = $this->validate_data($data);
            foreach($data_valid as $valid){
                if ($valid !== 1){
                    $valid = False;
                }
            }
            if ($valid && isset($_FILES['headline'])){
                //add backslash to any comma added in the title and replace old value
                $title = $data['title'];
                $title = str_replace(',', "\,", $title);
                $data['title'] = $title;

                //save image and add image url to data
                $img_data = file_get_contents($_FILES['headline']['tmp_name']);
                $image_url = $_FILES['headline']['name'];

                $data['headline'] = $image_url;
                $data['image_data'] = $img_data;

                //save image type to data as well
                $image_type = explode('/',mime_content_type($_FILES['headline']['tmp_name']))[1];
                $data['image_type'] = $image_type;

                //send processed data to film repository for saving
                $this->film_repository->add_film($data);

                $this->show_html(false);
            } else {
                $this->errorMessage = 'Invalid parameters.';
                $this->show_html(true);
            }
        } else {
            $this->errorMessage .= "Some parameters have not been given: ";
            foreach ($missing_params as $missing_param){
                $this->errorMessage .=  $missing_param;
            }
            $this->show_html(true);
        }
    }

    private function handle_image_upload(array $file_data){
        $this->film_repository->save_image($file_data);
    }

    private function show_html(bool $error = false){
        $header_view = new HeaderView("Add movies", true);
        var_dump($this->film_repository->get_genres());
        $form_view = new FormView($this->film_repository->get_genres());
        $error_view = new ErrorView($this->errorMessage);
        $movie_table = new FilmTableView($this->film_repository->get_movies());

        $header_view->generateHTML();
        $form_view->generateHTML();
        if($error){
            $error_view->generateHTML();
        }
        $movie_table->generateHTML();
    }

    private function validate_data(array $data): array{
        $data_valid = [];

        //check year
        $data_valid['year'] = preg_match('/^\d{4}$/', $data['year']);

        //check if genre is only letters
        $data_valid['genre'] = preg_match('/^"[a-zA-Z]{3,30}"$/',  $data['genre']);

        //check if duration is only digits
        $data_valid['duration'] = preg_match('/^\d{1,4}/', $data['duration']);

        return $data_valid;
    }

}
