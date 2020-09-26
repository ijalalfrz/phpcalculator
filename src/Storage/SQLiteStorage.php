<?php
namespace Jakmall\Recruitment\Calculator\Storage;

use Jakmall\Recruitment\Calculator\Interfaces\StorageConnectionInterface;
use Jakmall\Recruitment\Calculator\Interfaces\StorageBLLInterface;


class SQLiteStorage implements StorageConnectionInterface, StorageBLLInterface
{
    private $path;
    public $conn;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function connect()
    {
        if ($this->conn == null)
        {
            $this->conn = new \PDO('sqlite:'.$this->path);
            $this->conn->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
            // print("Success connect sqlite");
        }
    }


    public function createTable($table_name, $prop)
    {
        try{
            $sql = "CREATE TABLE IF NOT EXISTS ".$table_name." ( ";
            
            $idx = 0;
            foreach($prop as $k => $v)
            {   
                if ($idx == \sizeof($prop)-1)
                {
                    $sql = $sql.$k.' '.$v.')';
                } else {
                    $sql = $sql.$k.' '.$v.', ';
                }
                $idx+=1;
            }

            $this->conn->exec($sql);
            return TRUE;
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function selectAllColumn($table_name)
    {
        try {

            $sql = "SELECT * FROM ".$table_name;
            $stmt = $this->conn->query($sql);
    
            $data = [];
            while ($history = $stmt->fetchObject()) {
                $data[] = $history;
            }
    
            return $data;
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function getLastInsertedData($table_name)
    {
        try {

            $sql = "SELECT * FROM ".$table_name. " ORDER BY id DESC LIMIT 1";
            $stmt = $this->conn->query($sql);
    
            while ($history = $stmt->fetchObject()) {
                $data = $history;
            }
    
            return $data;
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function filterById($table_name, $id)
    {
        try {

            $sql = "SELECT * FROM ".$table_name. " WHERE id=:id";
            $stmt = $this->conn->prepare($sql);
    
            $stmt->execute([
                'id' => $id
            ]);
    
            $data = $stmt->fetchObject();        
    
            return $data;
        } catch(\PDOException $e) {
            throw $e;
        }
    }

    public function filterByColumn($table_name, $column)
    {
        try {

            $sql = "SELECT * FROM ".$table_name;
    
            $where_or = ' ';
    
            $idx = 0;
           
            $values = [];
            foreach($column as $k => $list_val) {
                
                foreach($list_val as $v) {
                    $values[':'.$idx.$k] = ucfirst($v);
                    if ($idx == 0) {
                        $where_or = $where_or.'WHERE '.$k.' = :'.$idx.$k;
                    } else {
                        $where_or = $where_or.' OR '.$k.' = :'.$idx.$k;
                    }
                    $idx++;
                }

            }
            
            $sql = $sql.$where_or;
            // print($sql);

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($values);

            $data = [];
            while ($history = $stmt->fetchObject()) {
                $data[] = $history;
            }
    
            return $data;
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function deleteAllData($table_name)
    {
        try{
            $sql = "DELETE FROM ".$table_name;
            $this->conn->exec($sql);
            return TRUE;
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function insert($table_name, $data)
    {

        $sql = "INSERT INTO ".$table_name." ";
        $data_column = '';
        $prep_value = '';
        $prep_value_list = [];
        $idx = 0;

        try{

            foreach($data as $column => $value)
            {
                if ($idx == \sizeof($data)-1)
                {
                    $data_column = $data_column.$column.'';
                    $prep_value = $prep_value.':'.$column;
                } else {
                    $data_column = $data_column.$column.',';
                    $prep_value = $prep_value.':'.$column.',';
                }
                array_push($prep_value_list, ':'.$column);
                $idx += 1;
            }
            $sql = $sql."(".$data_column.") VALUES(".$prep_value.")";

            $stmt = $this->conn->prepare($sql);

            $idx = 0;
            $values = [];
            foreach($prep_value_list as $prep)
            {
                $values[$prep] = $data[ \str_replace(':','',$prep)];
                $idx += 1;
            }
            $stmt->execute($values);
            return $this->conn->lastInsertId();
        } catch(\PDOException $e) {
            throw $e;
        }
    }

    public function deleteById($table_name, $id)
    {
        try {

            $sql = "DELETE FROM ".$table_name. " WHERE id=:id";
            $stmt = $this->conn->prepare($sql);
    
            $stmt->execute([
                'id' => $id
            ]);
        
    
            return true;
        } catch(\PDOException $e) {
            throw $e;
        }
    }
}

?>