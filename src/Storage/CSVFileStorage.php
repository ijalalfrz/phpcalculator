<?php
namespace Jakmall\Recruitment\Calculator\Storage;

use Jakmall\Recruitment\Calculator\Interfaces\StorageConnectionInterface;
use Jakmall\Recruitment\Calculator\Interfaces\StorageBLLInterface;


class CSVFileStorage implements StorageConnectionInterface, StorageBLLInterface
{
    private $csv_path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function connect()
    {
        if (!is_dir($this->path)) {
            throw new Exception("No db directory in root project folder");
        }
    }

    public function createTable($table_name, $column)
    {
        $header = [];
        $header_column = array_keys($column);
        foreach($header_column as $hc) {
            array_push($header, $hc);
        }

        $this->csv_path = $this->path.'/'.$table_name.'.csv'; 

        if (!\file_exists($this->csv_path)) {
            $file = \fopen($this->csv_path, 'w');
            \fputcsv($file, $header);
            \fclose($file);
        }

    }

    public function selectAllColumn($table_name)
    {
        $file = fopen($this->csv_path, 'r');
        $header = fgetcsv($file, 1024);
        \fclose($file);

        $mapping_idx = [];
        $idx = 0;
        foreach ($header as $head) {
            
            $mapping_idx[$head] = $idx;
            $idx ++;
        }


        $data_column = [];
        $data = [];
        $idx = 0;
        $file = fopen($this->csv_path, 'r');
        while(!feof($file))
        {
            $csv_line = fgetcsv($file, 1024);

            if ($idx != 0 && $csv_line) {
                
                foreach ($header as $head) {
                    $data_column[$head] = $csv_line[$mapping_idx[$head]];
                }

                $data[] = (object) $data_column;
            }

            $idx++;
        }
        fclose($file);

        return $data;
    }

    public function filterByColumn($table_name, $column)
    {
        $file = fopen($this->csv_path, 'r');
        $header = fgetcsv($file, 1024);
        \fclose($file);

        $mapping_idx = [];
        $idx = 0;
        foreach ($header as $head) {
            $mapping_idx[$head] = $idx;
            $idx ++;
        }

        // read data
        $data = [];
        $data_column = [];
        $file = fopen($this->csv_path, 'r');
        while(!feof($file))
        {
            $csv_line = fgetcsv($file, 1024);

            foreach($column as $k => $filter_values) {
                if ($csv_line && \in_array(\lcfirst($csv_line[$mapping_idx[$k]]), $filter_values)) {
                    foreach ($header as $head) {
                        $data_column[$head] = $csv_line[$mapping_idx[$head]];
                    }
                    $data[] = (object) $data_column;
                }
            }

            $idx++;
        }
        fclose($file);

        return $data;
    }

    public function deleteAllData($table_name)
    {
        try {

            $file = fopen($this->csv_path, 'r');
            $header = fgetcsv($file, 1024);
            \fclose($file);
    
            $file = \fopen($this->csv_path, 'w');
            \fputcsv($file, $header);
            \fclose($file);
    
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function insert($table_name, $data)
    {
        try {

            $file = \fopen($this->csv_path, 'a');
            $values = array_values($data);
            \fputcsv($file, $values);        
            \fclose($file);
    
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function filterById($table_name, $id)
    {
        try {
            $file = \fopen($this->csv_path, 'r');
    
            $header = fgetcsv($file, 1024);
            \fclose($file);
    
            $mapping_idx = [];
            $idx = 0;
            foreach ($header as $head) {
                
                $mapping_idx[$head] = $idx;
                $idx ++;
            }
    
    
            $data_column = [];
            $data = NULL;
            $idx = 0;
            $file = fopen($this->csv_path, 'r');

            $found = false;
            while(!feof($file) && !$found)
            {
                $csv_line = fgetcsv($file, 1024);
    
                if ($idx != 0 && $csv_line) {
                    
                    foreach ($header as $head) {
                        $data_column[$head] = $csv_line[$mapping_idx[$head]];
                    }
                    if ($id == $data_column['id']) {

                        $data = (object) $data_column;
                        $found = true;
                    }
                }
    
                $idx++;
            }
            fclose($file);
            return $data;
        } catch (Exception $e) {
            throw $e;
        }
        
    }

    public function deleteById($table_name, $id)
    {
        try{

            $file = fopen($this->csv_path,'r');
            $path = \explode('/', $this->csv_path)[0];
            $temp_file = fopen($path.'/file_temp.csv','w');
    
    
            while (($data = fgetcsv($file, 1000)) !== FALSE){
                if($data[0] == $id){
                    continue;
                }
                fputcsv($temp_file,$data);
            }
            fclose($file);
            fclose($temp_file);
            rename($path.'/file_temp.csv', $this->csv_path);
            return true;
        } catch(Exception $e) {
            throw $e;
        }
    }
}