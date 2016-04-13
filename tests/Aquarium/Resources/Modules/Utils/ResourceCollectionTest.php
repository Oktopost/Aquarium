<?php
namespace Aquarium\Resources\Modules\Utils;


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
	
	public function test_add_AddArray() 
	{
		$collection = new ResourceCollection();
		$collection->add(['a', 'b', 'c']);
		
		$this->assertEquals(['a', 'b', 'c'], $collection->get());
	}
	
	public function test_add_AddArrayWithAlreadyExistingValues() 
	{
		$collection = new ResourceCollection();
		$collection->add(['a', 'b', 'c']);
		$collection->add(['n', 'b', 'a']);
		
		$this->assertEquals(['a', 'b', 'c', 'n'], $collection->get());
	}
	
	public function test_add_AddArray_ReturnsSelf() 
	{
		$collection = new ResourceCollection();
		$collection->add(['a', 'b', 'c']);
		$collection->add(['n', 'b', 'a']);
		
		$this->assertEquals(['a', 'b', 'c', 'n'], $collection->get());
	}
	
	public function test_add_AddResourceCollection() 
	{
		$collection = new ResourceCollection();
		$collection->add(['a', 'b', 'c']);
		
		$added = new ResourceCollection();
		$added->add(['n', 'b', 'h']);
		
		$collection->add($added);
		
		$this->assertEquals(['a', 'b', 'c', 'n', 'h'], $collection->get());
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
	
	
	public function test_replace_ElementNotFound() 
	{
		$collection = new ResourceCollection();
		$collection->add('a');
		$collection->replace('b', 'c');
		
		$this->assertEquals(['a'], $collection->get());
	}
	
	public function test_replace_SingleElement() 
	{
		$collection = new ResourceCollection();
		$collection->add('a');
		$collection->replace('a', 'c');
		
		$this->assertEquals(['c'], $collection->get());
	}
	
	public function test_replace_NumberOfElements() 
	{
		$collection = new ResourceCollection();
		$collection->add('a')->add('b')->add('c');
		$collection->replace('b', 'd');
		
		$this->assertEquals(['a', 'd', 'c'], $collection->get());
	}
	
	
	public function test_remove_ElementNotFound() 
	{
		$collection = new ResourceCollection();
		$collection->add('a');
		$collection->remove('b');
		
		$this->assertEquals(['a'], $collection->get());
	}
	
	public function test_remove_ElementFound() 
	{
		$collection = new ResourceCollection();
		$collection->add('a')->add('b');
		$collection->remove('b');
		
		$this->assertEquals(['a'], $collection->get());
	}
	
	public function test_remove_SingleElementAsArray() 
	{
		$collection = new ResourceCollection();
		$collection->add('b')->add('c');
		$collection->remove(['b']);
		
		$this->assertEquals(['c'], $collection->get());
	}
	
	public function test_remove_NumberOfElements() 
	{
		$collection = new ResourceCollection();
		$collection->add('a')->add('b')->add('c')->add('d');
		$collection->remove(['b', 'd']);
		
		$this->assertEquals(['a', 'c'], $collection->get());
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
	
	public function test_get_FilterUsed_FilterApplied() 
	{
		$collection = new ResourceCollection();
		$collection->add('a')->add('1abc')->add('abc')->add('1nnn');
		$this->assertEquals(['1abc', '1nnn'], $collection->get('1*'));
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