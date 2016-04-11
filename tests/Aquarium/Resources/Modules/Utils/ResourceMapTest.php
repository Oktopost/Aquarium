<?php
namespace Aquarium\Resources\Modules\Utils;


class ResourceMapTest extends \PHPUnit_Framework_TestCase
{
	public function test_map()
	{
		$map = new ResourceMap();
		$map->map('a', 'b');
		
		$this->assertEquals(['b' => 'a'], $map->getMap());
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
}
