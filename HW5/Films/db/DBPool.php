<?php


namespace db;

use \PDO;
class DBPool
{
    /**
     * @var PDO
     */
    private static $PDO;

    /**
     * @return PDO
     */
    public static function getInstance(){
        if (null === self::$PDO){
            try{
                self::$PDO = new PDO("mysql:dbname=sofa_5_dz", "root", "", [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
                ]);
            } catch (\PDOException $e){
                var_dump($e);
                die();
            }
        }

        return self::$PDO;
    }
}