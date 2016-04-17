<?php
namespace Aquarium\Resources\DefinitionStrategy;


use Aquarium\Resources\Config;
use Aquarium\Resources\Package;
use Aquarium\Resources\Utils\Builder;


class CompiledPackagesTest extends \PHPUnit_Framework_TestCase
{
	const PATH = __DIR__ . '/CompiledPackagesClasses';
	
	
	public function setUp()
	{
		Builder::setTestMode(true);
	}
	
	
	/**
	 * @runInSeparateProcess
	 * @expectedException \Exception
	 */
	public function test_getNames_ThrowsException()
	{
		(new CompiledPackages())->getNames();
	}
	
	
	/**
	 * @runInSeparateProcess
	 */
	public function test_has()
	{
		Config::instance()->Directories->PhpTargetDir = self::PATH;
		$this->assertTrue((new CompiledPackages())->has('a/b'));
		$this->assertTrue((new CompiledPackages())->has('c/d'));
		$this->assertFalse((new CompiledPackages())->has('not/found'));
	}
	
	
	/**
	 * @runInSeparateProcess
	 * @expectedException  \Exception
	 */
	public function test_get_PackageNotFound_ErrorThrown()
	{
		Config::instance()->Directories->PhpTargetDir = self::PATH;
		(new CompiledPackages())->get('not/found');
	}
	
	/**
	 * @runInSeparateProcess
	 */
	public function test_get_PackageFound_PackageObjectReturned()
	{
		Config::instance()->Directories->PhpTargetDir = self::PATH;
		$this->assertInstanceOf(Package::class, (new CompiledPackages())->get('a/b'));
	}
	
	/**
	 * @runInSeparateProcess
	 */
	public function test_get_PackageFound_PackageIsBuilt()
	{
		Config::instance()->Directories->PhpTargetDir = self::PATH;
		$p = (new CompiledPackages())->get('a/b');
		
		$this->assertEquals(['1'], $p->Styles->get());
		$this->assertEquals(['2'], $p->Scripts->get());
		$this->assertEquals(['3'], $p->Requires->get());
	}
	
	/**
	 * @runInSeparateProcess
	 */
	public function test_get_LoadedPackageCached()
	{
		Config::instance()->Directories->PhpTargetDir = self::PATH;
		$cp = new CompiledPackages();
		$this->assertSame($cp->get('a/b'), $cp->get('a/b'));
	}
	
	/**
	 * @runInSeparateProcess
	 */
	public function test_get_DifferentPackageRequested()
	{
		Config::instance()->Directories->PhpTargetDir = self::PATH;
		$cp = new CompiledPackages();
		$this->assertNotSame($cp->get('a/b'), $cp->get('c/d'));
	}
}
