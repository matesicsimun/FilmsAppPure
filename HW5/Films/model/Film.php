<?php

namespace model;

class Film extends AbstractDBModel
{
    /**
     * Primary key
     * @var int
     */
    private $id;

    /**
     * Film title
     * @var string
     */
    private $title;

    /**
     * Film duration in minutes
     * @var int
     */
    private $duration;

    /**
     * Film genre as string
     * @var string
     */
    private $genre;

    /**
     * Film year
     * @var int
     */
    private $year;

    /**
     * Cover image URL - not used when persisting to database.
     * @var string
     */
    private $headline;

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

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param mixed $duration
     */
    public function setDuration($duration): void
    {
        $this->duration = $duration;
    }

    /**
     * @return mixed
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @param mixed $genre
     */
    public function setGenre($genre): void
    {
        $this->genre = $genre;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year): void
    {
        $this->year = $year;
    }

    /**
     * @return mixed
     */
    public function getHeadline()
    {
        return $this->headline;
    }

    /**
     * @param mixed $headline
     */
    public function setHeadline($headline): void
    {
        $this->headline = $headline;
    }

    public function set_attributes(array $attributes){
        $this->setYear($attributes['year']);
        $this->setTitle($attributes['title']);
        $this->setHeadline($attributes['headline']);
        $this->setGenre($attributes['genre']);
        $this->setDuration($attributes['duration']);

        if(isset($attributes["id"])){
            $this->setId($attributes["id"]);
        }
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        $str = '';
        $str .= '"'. $this->id . '"'. "," .
            '"'. $this->title . '"'. ",".
            '"'. $this->genre . '"'. "," .
            '"'. $this->year . '"'. "," .
            '"'  . $this->duration .'"'.  "," .'"'. $this->headline.'"';

        return $str;
    }

    public function getPrimaryKeyColumn()
    {
        return "FilmId";
    }

    public function getTable()
    {
        return "films";
    }

    /**
     * The columns in the database.
     * @return array
     */
    public function getColumns()
    {
        return ["Name", "GenreId",
               "Duration", "Year","Cover",
                "image_type"];
    }
}

