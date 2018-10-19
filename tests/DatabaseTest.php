<?php

namespace CrCms\DataCenter\Tests;

use CrCms\Tests\CreatesApplication;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class DatabaseTest extends BaseTestCase
{
    use CreatesApplication;

    public function testSimplePut()
    {
        $result = $this->driver()->put('x',1);
        $this->assertEquals(true,$result);
    }

    public function testPut()
    {
        $result = $this->driver()->put('y',1);
        $result = $this->driver()->put('z','a');
        $result = $this->driver()->put('a',$this->getStdClass());
        $result = $this->driver()->put('b',$this->getArray());
        $result = $this->driver()->put('c',true);
        $result = $this->driver()->put('d',20.01);
        $result = $this->driver()->put('e',20.00);

        $this->assertDatabaseHas($this->table(),['key'=>'y','value'=>1,'channel'=>$this->channel()]);
        $this->assertDatabaseHas($this->table(),['key'=>'z','value'=>'a','channel'=>$this->channel()]);
        $this->assertDatabaseHas($this->table(),['key'=>'a','value'=>serialize($this->getStdClass()),'channel'=>$this->channel()]);
        $this->assertDatabaseHas($this->table(),['key'=>'b','value'=>json_encode($this->getArray()),'channel'=>$this->channel()]);
        $this->assertDatabaseHas($this->table(),['key'=>'c','value'=>true,'channel'=>$this->channel()]);
        $this->assertDatabaseHas($this->table(),['key'=>'d','value'=>20.01,'channel'=>$this->channel()]);
        $this->assertDatabaseHas($this->table(),['key'=>'e','value'=>20.00,'channel'=>$this->channel()]);
    }

    /**
     * @depends testPut
     */
    public function testHas()
    {
        $this->assertEquals(
            true,$this->driver()->has('y')
        );
        $this->assertEquals(
            true,$this->driver()->has('z')
        );
        $this->assertEquals(
            true,$this->driver()->has('a')
        );
        $this->assertEquals(
            true,$this->driver()->has('b')
        );
        $this->assertEquals(
            true,$this->driver()->has('c')
        );
        $this->assertEquals(
            true,$this->driver()->has('d')
        );
        $this->assertEquals(
            true,$this->driver()->has('e')
        );
    }

    /**
     * @depends testHas
     */
    public function testGet()
    {

        $this->assertEquals(
            1,$this->driver()->get('y')
        );
        $this->assertEquals(
            'a',$this->driver()->get('z')
        );
        $this->assertEquals(
            $this->getStdClass(),$this->driver()->get('a')
        );
        $this->assertEquals(
            $this->getArray(),$this->driver()->get('b')
        );
        $this->assertEquals(
            true,$this->driver()->get('c')
        );
        $this->assertEquals(
            20.01,$this->driver()->get('d')
        );
        $this->assertEquals(
            20.00,$this->driver()->get('e')
        );
    }

    /**
     * @depends testGet
     */
    public function testAll()
    {
        $data = $this->driver()->all();

        foreach ($data as $key => $value) {
            $this->assertEquals(
                $value,$this->driver()->get($key)
            );
        }
    }

    /**
     * @depends testAll
     */
    public function testDelete()
    {
        $keys = [
            'y','z','a','b','c','d','e'
        ];
        foreach ($keys as $key) {
            $this->driver()->delete($key);
            $this->assertEquals(
                false,$this->driver()->has($key)
            );
        }
    }

    protected function table(): string
    {
        return config('data-center.connections.database.table');
    }

    protected function channel(): string
    {
        return config('data-center.connections.database.channel');
    }

    protected function driver()
    {
        return $this->app->make('data-center.connection');
    }

    protected function getStdClass()
    {
        $class = new \stdClass();
        $class->b = 1;
        $class->c = 2;
        return $class;
    }

    protected function getArray()
    {
        return ['a'=>1,'b'=>2];
    }
}
