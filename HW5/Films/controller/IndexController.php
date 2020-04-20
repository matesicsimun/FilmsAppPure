<?php

namespace controller;

use view\AlphabetView;
use view\ErrorView;
use view\FilmTitleView;
use view\HeaderView;


class IndexController extends AbstractController {

    private $letter;
    private $film_repository;
    private $error_msg;

    public function __construct($letter = null, \FilmRepositoryInterface $film_repository) {
        $this->letter = $letter;
        $this->film_repository = $film_repository;
    }

    protected function doJob() {

        if (!ctype_alpha($this->letter)){
            $this->error_msg = "Not a letter.";
            $this->show_html(true);
        } else {

            if ($this->letter != null){
                $movies_list = $this->film_repository->get_movies($this->letter);
                if (empty($movies_list)){
                    $this->error_msg = "No movies with that letter.";
                    $this->show_html(true);
                } else {
                    $this->show_html(false, $movies_list);
                }

            }
        }
    }

    private function show_html(bool $error = false, array $movies=null){
        $header_view = new HeaderView("Movies collection", false);
        $header_view->generateHTML();

        $alphabet_view = new AlphabetView(range('A','Z'));
        $alphabet_view->generateHTML();

        if($error){
            $error_view = new ErrorView($this->error_msg);
            $error_view->generateHTML();
        } else {
            $film_view = new FilmTitleView($movies);
            $film_view->generateHTML();
        }
    }
}
