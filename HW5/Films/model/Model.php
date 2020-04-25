<?php
namespace model;

interface Model extends \Serializable {
    public function equals(Model $model);
}