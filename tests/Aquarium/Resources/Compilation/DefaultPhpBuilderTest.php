<?php
namespace Aquarium\Resources\Compilation;


use Aquarium\Resources\Config;
use Aquarium\Resources\Package;
use Aquarium\Resources\Utils\PackageBuilder;
use Aquarium\Resources\DefinitionStrategy\CompiledPackages;


class DefaultPhpBuilderTest extends \PHPUnit_Framework_TestCase
{
	const PATH = __DIR__ . '/DefaultPhpBuilderDir';
	
	
	public function setUp()
	{
		PackageBuilder::setTestMode(true);
	}
	
	
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
		
		$p->Inscribed->add('ins-a');
		$p->Inscribed->add('ins-1');
		
		$p->Styles->add('style1');
		$p->Styles->add('style2');
		
		$p->Scripts->add('script-a');
		$p->Scripts->add('script-b');
		
		Config::instance()->directories()->CompiledResourcesDir = 'a';
		Config::instance()->directories()->PhpTargetDir = self::PATH;
		
		
		// TEST:
		(new DefaultPhpBuilder())->buildPhpFile($p);
		
		
		$this->assertFileExists(
			self::PATH . '/' . 
			Utils::PACKAGE_CLASS_NAME_PREFIX . $p->getName(Utils::PACKAGE_PATH_SEPARATOR) . '.php',
			'PHP class file was not created or created in wrong directory!');
		
		
		$pLoaded = (new CompiledPackages())->get($p->Name);
		
		$this->assertEquals(['req-a', 'req-1'],			$pLoaded->Requires->get());
		$this->assertEquals(['script-a', 'script-b'],	$pLoaded->Scripts->get());
		$this->assertEquals(['style1', 'style2'],		$pLoaded->Styles->get());
		$this->assertEquals(['ins-a', 'ins-1'],			$pLoaded->Inscribed->get());
	}
}