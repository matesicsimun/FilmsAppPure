<?php


namespace db;


interface Persistance
{
    public function persist(array $data);

    public function get(int $id);

}