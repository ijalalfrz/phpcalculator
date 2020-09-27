<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Jakmall\Recruitment\Calculator\Storage\SQLiteStorage;

class SQLiteStorageTest extends TestCase
{
    protected function setUp():void
    {
        $this->storage = new SQLiteStorage('tests/db/test.db');
        $this->storage->connect();
        
    }

    public function testCreateTable()
    {
        $table_prop = [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT',
            'command' => 'VARCHAR(255) NOT NULL',
            'description' => 'VARCHAR(255) NOT NULL',
            'result' => 'INTEGER NOT NULL',
            'output' => 'VARCHAR(255) NOT NULL',
            'time' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
        ];

        $data = $this->storage->createTable('test', $table_prop);
 
        $this->assertTrue($data);
    }

    public function testInsert()
    {
        $data = [
            'id' => null,
            'command' => 'test',
            'description' => 'test',
            'result' => 1,
            'output' => 'test',
            'time' => date("Y-m-d H:i:s")
        ];

        $id = $this->storage->insert('test',$data);

        $this->assertEquals(\gettype($id), 'string');
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

    public function testGetLastId()
    {
        $data = $this->storage->getLastInsertedData('test');
        $this->assertIsObject($data);
    }

    public function testGetById()
    {
        $last_data = $this->storage->getLastInsertedData('test');
        
        $data = $this->storage->filterById('test', $last_data->id);
        $this->assertIsObject($data);
    }

    public function testDeleteById()
    {
        $last_data = $this->storage->getLastInsertedData('test');
        
        $status = $this->storage->deleteById('test', $last_data->id);
        $this->assertTrue($status);
    }

    public function testDeleteAll()
    {
        $status = $this->storage->deleteAllData('test');
        $this->assertTrue($status);
    }

    

}