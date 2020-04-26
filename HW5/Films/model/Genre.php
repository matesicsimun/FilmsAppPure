<?php

namespace model;

class Genre extends AbstractDBModel {
    private $name;
    private $id;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }



    public function getPrimaryKeyColumn()
    {
        return "GenreId";
    }

    public function getTable()
    {
        return "genre";
    }

    public function getColumns()
    {
        return ["GenreId", "Name"];
    }
}
