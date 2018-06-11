<?php
namespace helpers;

include "config.php";

class DBHelper
{

    public $conn;

    static private $instance;

    public function __construct()
    {
        $this->conn = mysqli_connect(DBHOST, DB_USER, DB_PASSWORD);
        mysqli_select_db($this->conn, DB_NAME);
        mysqli_set_charset($this->conn, "utf8");
    }

    static public function instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new DBHelper();
        }
        return self::$instance;
    }

    public function query($query)
    {
        $result = mysqli_query($this->conn, $query);

        if (!$result)
        {
            $this->debug($query);
        }

        if (mysqli_num_rows($result) == 0)
        {
            return array();
        }

        $arrRes = array();
        while ($row = mysqli_fetch_assoc($result))
        {
            $arrRes[] = $row;
        }

        return $arrRes;
    }

    public function count($table, $opts = array())
    {
        $where = "";

        if (!empty($opts['where']))
        {
            $where = $this->where($opts['where']);
        }

        $query = "SELECT COUNT(*) AS result FROM ".$table.$where;
        $row = $this->queryOne($query);
        //print_r($query);
        return (int)$row['result'];

    }

    public function insert($table, $data)
    {
        $fields = $this->escapeArray(array_keys($data));
        $values = $this->escapeArray(array_values($data));

        foreach ($values as $key => $val)
        {
            if (is_null($val))
            {
                $values[$key] = 'NULL';
            }
            else
            {
                $values[$key] = "'$val'";
            }
        }

        $query = "INSERT INTO $table(".join(",",$fields).") VALUES(".join(",", $values).")";
        //print_r($query);
        return mysqli_query($this->conn, $query);
    }

    private function queryOne($query)
    {
        $result = mysqli_query($this->conn, $query);
        if (!$result)
        {
            $this->debug($query);
            return false;
        }

        if (mysqli_num_rows($result) == 0)
        {
            $this->debug($query);
            return false;
        }

        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        return $row;
    }

    public function select($table, $opts = array())
    {
        $fields = "*";
        $where = '';
        $order = '';

        if (!empty($opts['fields'])) {
            if (is_array($opts['fields'])) {
                $fields = join(",", $opts['fields']);
            } else {
                $fields = $opts['fields'];
            }
        }

        if (!empty($opts['where'])) {
            $where = $this->where($opts['where']);
        }

        if (!empty($opts['order'])) {
            $order = "ORDER BY " . $opts['order'];
        }

        $query = "SELECT $fields FROM $table $where $order";

        if (!empty($opts['limit'])) {
            if ($opts['limit'] === 1 || $opts['limit'] == '1') {
                return $this->queryOne($query." LIMIT 1");
            }

            $query .= " LIMIT ".$opts['limit'];
        }

        return $this->query($query);
    }

    public function delete($table, $whereCondition = array())
    {
        $where = '';

        if (!empty($whereCondition)) {
            $where = $this->where($whereCondition);
        }

        $query = "DELETE FROM $table $where";

        return $this->execute($query);
    }

    public function selectOne($table, $opts = array())
    {
        $opts['limit'] = 1;
        return $this->select($table, $opts);
    }

    public function update($table, $data, $opts = array())
    {
        $where = "";
        if (!empty($opts['where'])) {
            $where = $this->where($opts['where']);
        }

        $update = array();
        foreach ($data as $field => $value) {
            if (is_null($value)) {
                $update[] = "`$field` = NULL";
            } else {
                $update[] = "`$field` = '".$this->escape($value)."'";
            }
        }

        $query = "UPDATE $table SET ".join(" , ", $update)." $where";

        return $this->execute($query);
    }

    private function where($condition)
    {
        $where = "";

        if (!empty($condition) && is_array($condition))
        {
            $where = array();
            foreach ($condition as $field => $value)
            {
                if (is_numeric($field) || empty($field))
                {
                    $where[] = " $value ";
                }
                else if (is_null($value))
                {
                    $where[] = " $field is null";
                }
                else
                {
                    $where[] = " $field = '".$this->escape($value)."' ";
                }
            }
            if (!empty($where))
            {
                $where = " WHERE " . join(" AND ", $where);
            }
        }
        else if (!empty($condition))
        {
            $where = " WHERE " . $condition;
        }
        return $where;
    }

    public function escape($str)
    {
        if (is_null($str)) return null;
        return mysqli_real_escape_string($this->conn, $str);
    }

    public function execute($query)
    {
        return mysqli_query($this->conn, $query);
    }

    public function escapeArray($arr)
    {
        foreach ($arr as $key => $value) {
            if (!is_null($value))
            {
                $arr[$key] = $this->escape($value);
            }
        }
        return $arr;
    }

    protected function debug($query)
    {
        echo "<br>Error in the sentence: ". $query . "<br>";
        $e = new Exception();
        print($e->getTraceAsString());
    }
}
