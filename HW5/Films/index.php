<?php

use db\DBFilmRepository;
use db\FilmRepository;

require_once './lib/global.php';
require_once "db/FilmRepository.php";

$controller = null;

// File database
//$film_repository = FilmRepository::get_instance();
//$film_repository->set_filename("db/films.txt");

$film_repository = DBFilmRepository::getInstance();

switch (get("action")) {
    case "get":
        $controller = new controller\RetrieveImageController(get("id"), $film_repository) ;
        break;
    case "add":
        $controller = new controller\AddController($_POST, $film_repository);
        break;
    case "delete":
        $controller = new controller\DeleteController(get("id"), $film_repository);
        break;
    default:
        $controller = new controller\IndexController(get("letter"), $film_repository);
}

$controller->doAction();
