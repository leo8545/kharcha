<?php


namespace App\Models;

use App\Database;


class Model
{
    protected $tableName = 'posts';

    protected $fillables = [];

    protected $db;

    private $stmt;

    private string $queryString;

    public function __construct()
    {
        $this->db = Database::getInstance()->makeConnection();
        $this->queryString = '';
    }

    public function select(string $col = "*")
    {
        $this->queryString = "SELECT {$col} FROM `{$this->tableName}`";
        return $this;
    }

    public function insert($args)
    {
        $this->queryString = "INSERT INTO `{$this->tableName}`(";
        if(count($this->fillables) > 0) {
            foreach($this->fillables as $field) {
                $this->queryString .= "`{$field}`";
                if(end($this->fillables) !== $field) {
                   $this->queryString .= ", ";
                }
            }
            $this->queryString .= ") VALUES (";
            foreach($args as $col => $val) {
                $this->queryString .= "\"$val\"";
                if(end($args) !== $val) {
                    $this->queryString .= ", ";
                }
            }
            $this->queryString .= ")";
        }

        return $this->execute();
    }

    public function where($colName, $op, $value)
    {
        $this->queryString .= " WHERE {$colName} {$op} \"{$value}\"";
        return $this;
    }

    public function whereMany(array $where)
    {
        $this->queryString .= ' WHERE';
        foreach( $where as $w ) {
            $this->queryString .= " {$w[0]} {$w[1]} \"{$w[2]}\"";
            if(end($where) !== $w) {
                $this->queryString .= ' AND';
            }
        }
        return $this;
    }

    public function limit(int $limit)
    {
        $this->queryString .= " LIMIT {$limit}";
        return $this;
    }

    public function orderBy(string $col, $order = 'ASC')
    {
        $this->queryString .= " ORDER BY {$col} {$order}";
        return $this;
    }

    private function execute() : bool
    {
        if(empty($this->queryString)) return false;
        $this->stmt = $this->db->prepare($this->queryString);
        return $this->stmt->execute();
    }

    public function get()
    {
        if($this->execute()) {
            return $this->stmt->fetchAll($this->db::FETCH_ASSOC);
        }
        return null;
    }

    public function all()
    {
        return $this->select()->get();
    }

    public function first()
    {
        return $this->select()->limit(1)->get();
    }
}