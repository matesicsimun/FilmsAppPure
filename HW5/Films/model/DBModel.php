<?php

namespace model;


interface DBModel extends Model{
    public function save();

    public function load($pk);

    public function delete();
}