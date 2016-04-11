<?php
namespace Aquarium\Resources\Modules;


use Aquarium\Resources\Package;
use Aquarium\Resources\Modules\Utils\ResourceCollection;


class PackageTest extends \PHPUnit_Framework_TestCase 
{
	public function test_Name() {
		$p = new Package('package/name');
		$this->assertEquals('package/name', $p->Name);
	}
	
	public function test_getPath_FullPath() {
		$p = new Package('package/name');
		$this->assertEquals(['package', 'name'], $p->Path);
		
		$p = new Package('a');
		$this->assertEquals(['a'], $p->Path);
	}
	
	public function test_getPath_ValidPartOfPath() {
		$p = new Package('package/name');
		$this->assertEquals('package', $p->getPath(0));
		$this->assertEquals('name', $p->getPath(1));
	}
	
	public function test_getPath_InvalidPartOfPath() {
		$p = new Package('package/name');
		$this->assertNull($p->getPath(-1));
		$this->assertNull($p->getPath(2));
	}
	
	
	public function test_Properties() 
	{
		$p = new Package('package/name');
		
		$this->assertInstanceOf(ResourceCollection::class, $p->Requires);
		$this->assertInstanceOf(ResourceCollection::class, $p->Scripts);
		$this->assertInstanceOf(ResourceCollection::class, $p->Styles);
	}
}
