<?php

class DataBase
{
    private static $db = null;
    
    private $mysqli;

    const SYM_QUERY = "{?}";

    
    public static function getDB()
    {
        if (self::$db === null) {
            self::$db = new self();
        }

        return self::$db;
    }
    
    private function __construct()
    {
        $this->mysqli = new mysqli(
            'localhost',
            'root',
            '',
            'climaker'
        );
    }
    
    private function getQuery($query, $params = null)
    {
        if ($params !== null) {
            for ($i = 0; $i < count($params); $i++) {
                $pos = strpos($query, self::SYM_QUERY);
                $arg = "'" . $this->mysqli->real_escape_string($params[$i]) . "'";
                $query = substr_replace($query, $arg, $pos, strlen(self::SYM_QUERY));
            }
        }

        return $query;
    }

    public function select($query, $params = null)
    {
        $result_set = $this->mysqli->query($this->getQuery($query, $params));

        return !$result_set ? [] : $this->resultSetToArray($result_set);
    }

    public function selectRow($query, $params = null)
    {
        $result_set = $this->mysqli->query($this->getQuery($query, $params));

        return $result_set->num_rows != 1 ? null : $result_set->fetch_assoc();
    }

    public function query($query, $params = null)
    {
        $success = $this->mysqli->query($this->getQuery($query, $params));

        if ($success) {
            return $this->mysqli->insert_id === 0 ? true : $this->mysqli->insert_id;
        }

        return false;
    }

    private function resultSetToArray($result_set)
    {
        $array = array();

        while (($row = $result_set->fetch_assoc()) != false) {
            $array[] = $row;
        }

        return $array;
    }
}