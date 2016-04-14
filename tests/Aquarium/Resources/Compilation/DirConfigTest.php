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
	
	/**
	 * @expectedException \Exception
	 */
	public function test_addSourceDir_DirectoryAlreadyExists_ErrorThrown()
	{
		$d = new DirConfig();
		
		$d->addSourceDir('a');
		$d->addSourceDir('a');
	}
}