<?php

namespace model;

use app\model\NotFoundException;
use db\DBPool;

abstract class AbstractDBModel implements DBModel{

    private $pk;

    protected $data;

    public function __construct()
    {
        $this->data = new \stdClass();
    }

    public function load($pk){

        $sql = "SELECT * FROM " . $this->getTable() . " WHERE " .
            $this->getPrimaryKeyColumn() . " = ?";

        $stmt = DBPool::getInstance()->prepare($sql);
        $stmt->execute(array($pk));

        if (1 !== $stmt->rowCount()){
            throw new NotFoundException();
        }

        $this->data = $stmt->fetch();
        $pkCol = $this->getPrimaryKeyColumn();
        $this->pk = $this->data->$pkCol;
    }

    public function delete(){
        if (null === $this->pk){
            return;
        }
        DBPool::getInstance()->prepare("DELETE FROM " . $this->getTable() . " WHERE "
            . $this->getPrimaryKeyColumn() . " =?")->execute(array($this->pk));
        $this->pk = null;
    }

    public function save(){

        $columns = $this->getColumns();

        if (null === $this->pk){
            $values = array();
            $placeHolders = array();

            foreach($columns as $column){
                $values[] = $this->data->$column;
                $placeHolders[] = "?";
            }

            $sql = "INSERT INTO " . $this->getTable() . " (". implode(",", $columns)
                    . ") VALUES (" . implode(",", $placeHolders) . ")";

            DBPool::getInstance()->prepare($sql)->execute($values);

            $this->pk  = DBPool::getInstance()->lastInsertId();
        } else {
            $values = array();
            $placeHolders = array();

            foreach ($columns as $column){
                $values[] = $this->data->$column;
                $placeHolders = $column . " = ?";
            }

            $values[] = $this->pk;

            $sql = "UPDATE " . $this->getTable() . " SET " . implode(", ", $placeHolders)
                    . " WHERE " . $this->getPrimaryKeyColumn() . " = ?";

            DBPool::getInstance()->prepare($sql)->execute($values);
        }
    }

    public function equals(Model $model){
            if (get_class($this) !== get_class($model)){
                return false;
            }

            return $this->pk === $model->getPrimaryKey();
    }

    public function serialize() {
        return serialize($this->data);
    }

    public function unserialize($string) {
        $this->data = unserialize($string);
    }


    public function getPrimaryKey() {
        return $this->pk;
    }

    public function __get($name) {
        return $this->data->$name;
    }

    public function __set($name, $value) {
        return $this->data->$name = $value;
    }

    public abstract function getPrimaryKeyColumn();

    public abstract function getTable();

    public abstract function getColumns();

    public function loadAll($where = null) {

        $sql = "SELECT * FROM " . $this->getTable() . " " .$where;

        $statement = DBPool::getInstance()->prepare($sql);
        $statement->execute();

        if (1 > $statement->rowCount()) {
            return null;
        }

        $resources = $statement->fetchAll();

        $collection = array();

        $className = get_class($this);
        //$attributes = $this->getColumns();
        foreach ($resources as $singleRow) {
            $model = new $className();
            $model->pk = $singleRow->{$this->getPrimaryKeyColumn()};
            $model->data = $singleRow;

            /*foreach ($attributes as $prop) {
                $model->$prop = $singleRow->{$prop};
            }*/

            $collection[] = $model;
        }

        return $collection;
    }

}