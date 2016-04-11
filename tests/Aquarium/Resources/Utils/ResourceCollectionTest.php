<?php
namespace Aquarium\Resources\Utils;


class ResourceCollectionTest extends \PHPUnit_Framework_TestCase
{
	public function test_add() 
	{
		$collection = new ResourceCollection();
		
		$collection->add('a');
		$collection->add('b');
		$this->assertEquals(['a', 'b'], $collection->get());
	}
	
	public function test_add_SameResourceIgnored() 
	{
		$collection = new ResourceCollection();
		
		$collection->add('a');
		$collection->add('a');
		$this->assertEquals(['a'], $collection->get());
	}
	
	public function test_add_ReturnSelf() 
	{
		$collection = new ResourceCollection();
		$this->assertSame($collection, $collection->add('a'));
		
		// Duplicate value still return self.
		$this->assertSame($collection, $collection->add('a'));
	}
	
	
	public function test_hasResource() 
	{
		$collection = new ResourceCollection();
		
		$collection->add('a');
		$collection->add('b');
		
		$this->assertTrue($collection->hasResource('a'));
		$this->assertFalse($collection->hasResource('c'));
	}
	
	
	public function test_hasAny() 
	{
		$collection = new ResourceCollection();
		
		$this->assertFalse($collection->hasAny());
		
		$collection->add('a');
		
		$this->assertTrue($collection->hasAny());
	}
	
	
	public function test_count() 
	{
		$collection = new ResourceCollection();
		
		$this->assertEquals(0, $collection->count());
		
		$collection->add('a');
		$collection->add('b');
		$collection->add('c');
		$collection->add('a');
		
		$this->assertEquals(3, $collection->count());
	}
	
	
	public function test_get_NoData_ReturnEmptyArray() 
	{
		$collection = new ResourceCollection();
		$this->assertEmpty($collection->get());
	}
	
	public function test_get_HasData_ReturnEmptyArray() 
	{
		$collection = new ResourceCollection();
		$collection->add('a');
		$this->assertEquals(['a'], $collection->get());
	}
	
	
	public function test_iteration() 
	{
		$expected = ['a', 'b', 'c'];
		$curr = 0;
		
		$collection = new ResourceCollection();
		$collection->add('a');
		$collection->add('b');
		$collection->add('c');
		
		foreach ($collection as $value) 
		{
			$this->assertEquals($expected[$curr++], $value);
		}
		
		$this->assertEquals(count($expected), $curr);
	}
}