<?php
namespace Aquarium\Resources\Utils;


class ResourceMapTest extends \PHPUnit_Framework_TestCase
{
	public function test_map_ToSingleElement()
	{
		$map = new ResourceMap();
		$map->map('a', 'b');
		
		$this->assertEquals(['b' => ['a']], $map->getMap());
	}
	
	public function test_map_ToArray()
	{
		$map = new ResourceMap();
		$map->map(['a', 'c'], 'b');
		
		$this->assertEquals(['b' => ['a', 'c']], $map->getMap());
	}
	
	
	public function test_apply_MapEmpty()
	{
		$map = new ResourceMap();
		
		$collection = new ResourceCollection();
		$collection->add('a')->add('b');
		
		$map->apply($collection);
		
		$this->assertEquals(['a', 'b'], $collection->get());
	}
	
	public function test_apply_OneToOneMap()
	{
		$map = new ResourceMap();
		$map->map('b', 'c');
		
		$collection = new ResourceCollection();
		$collection->add('a')->add('b');
		
		$map->apply($collection);
		
		$this->assertEquals(['a', 'c'], $collection->get());
	}
	
	public function test_apply_ManyToOneMap()
	{
		$map = new ResourceMap();
		$map->map(['b', 'a'], 'c');
		
		$collection = new ResourceCollection();
		$collection->add('a')->add('b')->add('n');
		
		$map->apply($collection);
		
		$this->assertEquals(['c', 'n'], $collection->get());
	}
	
	public function test_apply_ManyToOneMap_OrderRemains()
	{
		$map = new ResourceMap();
		$map->map(['b', 'c'], 'new');
		
		$collection = new ResourceCollection();
		$collection->add('prev')->add('b')->add('c')->add('next');
		
		$map->apply($collection);
		
		$this->assertEquals(['prev', 'new', 'next'], $collection->get());
	}
	
	public function test_apply_ManyMaps()
	{
		$map = new ResourceMap();
		$map->map(['b', 'c'], 'new');
		$map->map('a', 'n');
		
		$collection = new ResourceCollection();
		$collection->add('1')->add('b')->add('c')->add('a');
		
		$map->apply($collection);
		
		$this->assertEquals(['1', 'new', 'n'], $collection->get());
	}
	
	
	public function test_combine_NoChanges()
	{
		$original = new ResourceMap();
		$original->map('a', 'b');
		
		$modified = new ResourceMap();
		
		$original->modify($modified);
		
		$this->assertEquals(['b' => ['a']], $original->getMap());
	}
	
	public function test_combine_NewMapExists()
	{
		$original = new ResourceMap();
		$original->map('a', 'b');
		
		$modified = new ResourceMap();
		$modified->map('c', 'd');
		
		$original->modify($modified);
		
		$this->assertEquals(['b' => ['a'], 'd' => ['c']], $original->getMap());
	}
	
	public function test_combine_SingleValueModified()
	{
		$original = new ResourceMap();
		$original->map('a', 'b');
		
		$modified = new ResourceMap();
		$modified->map('b', 'c');
		
		$original->modify($modified);
		
		$this->assertEquals(['c' => ['a']], $original->getMap());
	}
	
	public function test_combine_OriginalResourceWasAnArrayOfResources()
	{
		$original = new ResourceMap();
		$original->map(['a1', 'a2'], 'b');
		
		$modified = new ResourceMap();
		$modified->map('b', 'c');
		
		$original->modify($modified);
		
		$this->assertEquals(['c' => ['a1', 'a2']], $original->getMap());
	}
	
	public function test_combine_OriginalNewResourceIsOneOfSourceResources()
	{
		$original = new ResourceMap();
		$original->map('a', 'b');
		
		$modified = new ResourceMap();
		$modified->map(['b', 'b2'], 'c');
		
		$original->modify($modified);
		
		$this->assertEquals(['c' => ['a', 'b2']], $original->getMap());
	}
	
	public function test_combine_OriginalNewResourceIsOneOfSourceResources_OriginalResourceSourceIsArrayOfResources()
	{
		$original = new ResourceMap();
		$original->map(['a1', 'a2'], 'b');
		
		$modified = new ResourceMap();
		$modified->map(['b0', 'b', 'b2'], 'c');
		
		$original->modify($modified);
		
		$this->assertEquals(['c' => ['b0', 'a1', 'a2', 'b2']], $original->getMap());
	}
	
	public function test_combine_Sanity()
	{
		$original = new ResourceMap();
		$original->map(['a1', 'a2'], 'b');
		$original->map('d', 'n');
		$original->map('only', 'old');
		
		$modified = new ResourceMap();
		$modified->map(['b0', 'b', 'b2'], 'c');
		$modified->map('only', 'new');
		$modified->map(['b', 'n'], 'm');
		
		$original->modify($modified);
		
		$this->assertEquals(
			[
				'c' => ['b0', 'a1', 'a2', 'b2'],
				'new' => ['only'],
				'old' => ['only'],
				'm' => ['a1', 'a2', 'd']
			], 
			$original->getMap());
	}
	
	
	public function test_getMapFor_NotFound_ReturnNull()
	{
		$map = new ResourceMap();
		$map->map('a', 'b');
		
		$this->assertNull($map->getMapFor('d'));
	}
	
	public function test_getMapFor_Found_ReturnValues()
	{
		$map = new ResourceMap();
		$map->map('a', 'b');
		$map->map(['a', 'n'], 'c');
		
		$this->assertEquals(['a'], $map->getMapFor('b'));
		$this->assertEquals(['a', 'n'], $map->getMapFor('c'));
	}
}
