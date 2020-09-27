<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Jakmall\Recruitment\Calculator\Storage\CSVFileStorage;

class CSVFileStorageTest extends TestCase
{
    protected function setUp():void
    {
        $this->storage = new CSVFileStorage('tests/db');
        $this->storage->connect();

        $table_prop = [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT',
            'command' => 'VARCHAR(255) NOT NULL',
            'description' => 'VARCHAR(255) NOT NULL',
            'result' => 'INTEGER NOT NULL',
            'output' => 'VARCHAR(255) NOT NULL',
            'time' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
        ];

        $data = $this->storage->createTable('test', $table_prop);
        
    }

    public function testInsert()
    {
        $data = [
            'id' => '1',
            'command' => 'test',
            'description' => 'test',
            'result' => 1,
            'output' => 'test',
            'time' => date("Y-m-d H:i:s")
        ];

        $id = $this->storage->insert('test',$data);

        $data['id'] = '2';
        $this->storage->insert('test',$data);

        $this->assertEquals(\gettype($id), 'boolean');
    }

    public function testFilterByColumn()
    {
        $filter = $this->storage->filterByColumn('test', ['command'=> ['test']]);
        $this->assertIsArray($filter);
    }

    public function testSelectAll()
    {
        $data = $this->storage->selectAllColumn('test');
        $this->assertIsArray($data);
    }


    public function testGetById()
    {
        $data = $this->storage->filterById('test', '1');
        $this->assertIsObject($data);
    }

    public function testDeleteById()
    {
        
        $status = $this->storage->deleteById('test', '2');
        $this->assertTrue($status);
    }

    public function testDeleteAll()
    {
        $status = $this->storage->deleteAllData('test');
        $this->assertTrue($status);
    }

    

}