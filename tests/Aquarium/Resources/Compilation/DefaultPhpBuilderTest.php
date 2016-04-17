<?php
namespace Aquarium\Resources\Compilation;


use Aquarium\Resources\Config;
use Aquarium\Resources\DefinitionStrategy\CompiledPackages;
use Aquarium\Resources\Package;


class DefaultPhpBuilderTest extends \PHPUnit_Framework_TestCase
{
	const PATH = __DIR__ . '/DefaultPhpBuilderDir';
	
	
	public static function setUpBeforeClass()
	{
		foreach (glob(self::PATH . '/*.php') as $file) { unlink($file); }
	}
	
	public static function tearDownAfterClass()
	{
		foreach (glob(self::PATH . '/*.php') as $file) { unlink($file); }
	}
	
	
	public function test_sanity() 
	{
		$p = new Package('a/b');
		
		$p->Requires->add('req-a');
		$p->Requires->add('req-1');
		
		$p->Styles->add('style1');
		$p->Styles->add('style2');
		
		$p->Scripts->add('script-a');
		$p->Scripts->add('script-b');
		
		Config::instance()->Directories->PhpTargetDir = self::PATH;
		
		
		// TEST:
		(new DefaultPhpBuilder())->buildPhpFile($p);
		
		
		$this->assertFileExists(
			self::PATH . '/' . 
			Utils::PACKAGE_CLASS_NAME_PREFIX . $p->getName(Utils::PACKAGE_PATH_SEPARATOR) . '.php',
			'PHP class file was not created or created in wrong directory!');
		
		
		$pLoaded = (new CompiledPackages())->get($p->Name);
		
		$this->assertEquals($pLoaded->Requires->get(), $p->Requires->get());
		$this->assertEquals($pLoaded->Scripts->get(), $p->Scripts->get());
		$this->assertEquals($pLoaded->Styles->get(), $p->Styles->get());
	}
}