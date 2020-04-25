<?php


interface FilmRepositoryInterface
{


    /**
     * Deletes database entry with id = $id
     * @param int $id The id of the entry to be deleted
     */
    public function delete(int $id);

    public function add_film(array $film_data);

    public function get_movies(string $first_letter);

    public function get_genres();

    public function save_image(array $image_data);

    public function get_image_url(int $id);

    public function get_image_data_and_type(int $id):array;
}