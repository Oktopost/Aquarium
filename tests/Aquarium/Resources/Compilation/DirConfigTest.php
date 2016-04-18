<?php
namespace Aquarium\Resources\Compilation;


class DirConfigTest extends \PHPUnit_Framework_TestCase
{
	public function test_addSourceDir()
	{
		$d = new DirConfig();
		
		$d->addSourceDir('a');
		$d->addSourceDir('b');
		
		$this->assertEquals(['a', 'b'], $d->ResourcesSourceDirs);
	}
	
	public function test_addSourceDir_DirectoryAlreadyExists_NotDuplicated()
	{
		$d = new DirConfig();
		
		$d->addSourceDir('a');
		$d->addSourceDir('a');
		
		$this->assertEquals(['a'], $d->ResourcesSourceDirs);
	}
	
	
	public function test_getRelativePathToSource_NotFound_ReturnFalse()
	{
		$d = new DirConfig();
		
		$d->addSourceDir('a');
		$d->ResourcesTargetDir = 'b';
		
		$this->assertFalse($d->getRelativePathToSource('c'));
	}
	
	public function test_getRelativePathToSource_FoundInTargetDir_FixedPathReturned()
	{
		$d = new DirConfig();
		
		$d->ResourcesTargetDir = 'b';
		
		$this->assertEquals('d', $d->getRelativePathToSource('b/d'));
	}
	
	public function test_getRelativePathToSource_FoundInOnOfSourceDirs_FixedPathReturned()
	{
		$d = new DirConfig();
		
		$d->ResourcesTargetDir = 'n';
		$d->addSourceDir('a');
		$d->addSourceDir('b');
		
		$this->assertEquals('d', $d->getRelativePathToSource('b/d'));
	}
	
	public function test_getRelativePathToSource_ResourceAlwaysReturnedWithoutSlash()
	{
		$d = new DirConfig();
		$d->ResourcesTargetDir = 'n/';
		$this->assertEquals('m', $d->getRelativePathToSource('n/m'));
		
		$d = new DirConfig();
		$d->ResourcesTargetDir = 'n';
		$this->assertEquals('m', $d->getRelativePathToSource('n/m'));
	}
}