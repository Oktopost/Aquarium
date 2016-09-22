<?php
namespace Aquarium\Resources\Compilation;


use Aquarium\Resources\Package;


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
	
	
	public function test_truncateResourcesToPublicDir_ScriptTrancated()
	{
		$d = new DirConfig();
		$d->RootWWWDirectory = 'b';
		
		$p = new Package('a');
		$p->Scripts->add('b/c');
		
		$d->truncateResourcesToPublicDir($p);
		
		$this->assertEquals(['c'], $p->Scripts->get());
	}
	
	public function test_truncateResourcesToPublicDir_StyleTrancated()
	{
		$d = new DirConfig();
		$d->RootWWWDirectory = 'b';
		
		$p = new Package('a');
		$p->Styles->add('b/c');
		
		$d->truncateResourcesToPublicDir($p);
		
		$this->assertEquals(['c'], $p->Styles->get());
	}
}