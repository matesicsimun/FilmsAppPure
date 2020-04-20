<?php

namespace controller;

class DeleteController extends AbstractController {

    private $id;
    private $film_repository;

    public function __construct(int $id, \FilmRepositoryInterface $film_repository) {
        $this->id = $id;
        $this->film_repository = $film_repository;
    }

    public function doAction() {
        $this->film_repository->delete($this->id);
        redirect("index.php?action=add");
    }

    protected function doJob() {

    }

}
