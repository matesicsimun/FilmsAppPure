<?php


interface FilmRepositoryInterface
{
    /**
     * Inserts the given data into the database
     * @param string $data The data to be persisted
     */
    public function insert(string $data);

    /**
     * Deletes database entry with id = $id
     * @param int $id The id of the entry to be deleted
     */
    public function delete(int $id);

    /**
     * Returns a string or array of strings that represent database entries.
     * If the id is presented, a string is returned.
     * If no id is presented, the whole database entry set is returned.
     * @param int ...$id The id of the database entry
     */
    public function select(... $id);

    public function add_film(array $film_data);

    public function get_movies(string $first_letter);

    public function get_genres();

    public function save_image(array $image_data);

    public function get_image_url(int $id);

    public function get_image_data_and_type(int $id):array;
}