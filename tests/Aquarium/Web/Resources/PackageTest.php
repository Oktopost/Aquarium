<?php
namespace Aquarium\Web\Resources;


class PackageTest extends \PHPUnit_Framework_TestCase {
	
	public function test_getName() {
		$p = new Package("package/name");
		$this->assertEquals("package/name", $p->getName());
		
		$p = new Package("d");
		$this->assertEquals("d", $p->getName());
	}
	
	public function test_getPath_FullPath() {
		$p = new Package("package/name");
		$this->assertEquals(['package', 'name'], $p->getPath());
		
		$p = new Package("a");
		$this->assertEquals(['a'], $p->getPath());
	}
	
	public function test_getPath_ValidPartOfPath() {
		$p = new Package("package/name");
		$this->assertEquals('package', $p->getPath(0));
		$this->assertEquals('name', $p->getPath(1));
	}
	
	public function test_getPath_InvalidPartOfPath() {
		$p = new Package("package/name");
		$this->assertNull($p->getPath(-1));
		$this->assertNull($p->getPath(2));
	}
}
