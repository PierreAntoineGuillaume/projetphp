<?php

namespace
{
    require_once('db/sql_access.php');
}


namespace db {

    class db_handler
    {
        private static $connection;


        public static function init () {
            $param = 'mysql:dbname=';
            $param .= \db\configuration::$daba;
            $param .= ";host=";
            $param .= \db\configuration::$host;

            $options = array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
            );


            try {
                self::$connection = new \PDO ($param,
                    \db\configuration::$name,
                    \db\configuration::$pass,
                    $options);
            } catch (\PDOException $e) {
                echo 'Connexion échouée : ' . $e->getMessage();
            }
    }// init

        public function __construct()
        {

        }

        public static function close()
        {
            self::$connection=null;
        }// db_handler

        public function query ($query) {
            //$this->connection->query($this->connection->real_escape_string($query));
            $answer = self::$connection->query($query);
            return $answer;
        }// query

        public function __destruct() {
        }// ~db_handler
    }
}