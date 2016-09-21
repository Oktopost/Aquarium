<?php
namespace Aquarium\Resources\DefinitionStrategy;


use Aquarium\Resources\Package;
use Aquarium\Resources\Utils\PackageBuilder;
use Aquarium\Resources\Package\IPackageBuilder;


class ClassMethodsTestHelper
{
	public function Package_a_b(IPackageBuilder $b)
	{
		$b->script('a');
		$b->style('b');
		$b->package('n');
	}
	
	public function Package_c_d(IPackageBuilder $b)
	{
		$b->script('a');
		$b->style('b');
		$b->package('n');
	}
	
	public function Package_case_TEST(IPackageBuilder $b)
	{
		
	}
}


class ClassMethodsTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		PackageBuilder::setTestMode(true);
	}
	
	
	public function test_has_Found()
	{
		$cm = new ClassMethods(ClassMethodsTestHelper::class);
		
		$this->assertTrue($cm->has('a/b'));
		$this->assertTrue($cm->has('c/d'));
		$this->assertFalse($cm->has('not/found'));
	}
	
	
	public function test_getNames()
	{
		$cm = new ClassMethods(ClassMethodsTestHelper::class);
		
		$result = $cm->getNames();
		
		$this->assertCount(3, $result);
		
		$this->assertTrue(array_search('a/b', $result) !== false);
		$this->assertTrue(array_search('c/d', $result) !== false);
		$this->assertTrue(array_search('case/TEST', $result) !== false);
	}
	
	
	public function test_get_PackageReturned()
	{
		$cm = new ClassMethods(ClassMethodsTestHelper::class);
		
		$this->assertInstanceOf(Package::class, $cm->get('a/b'));
	}
	
	public function test_get_PackageDefinedCorrectly()
	{
		$cm = new ClassMethods(ClassMethodsTestHelper::class);
		
		$p = $cm->get('a/b');
		
		$this->assertEquals(['a'], $p->Scripts->get());
		$this->assertEquals(['b'], $p->Styles->get());
		$this->assertEquals(['n'], $p->Requires->get());
	}
	
	public function test_get_PackageCached()
	{
		$cm = new ClassMethods(ClassMethodsTestHelper::class);
		$this->assertSame($cm->get('a/b'), $cm->get('a/b'));
	}
	
	public function test_get_DifferentPackage()
	{
		$cm = new ClassMethods(ClassMethodsTestHelper::class);
		$this->assertNotSame($cm->get('c/d'), $cm->get('a/b'));
	}
	
	/**
	 * @expectedException \Exception
	 */
	public function test_get_PackageNotFound_ErrorThrown()
	{
		$cm = new ClassMethods(ClassMethodsTestHelper::class);
		$cm->get('not/found');
	}
	
	
	public function test_methodNameCaseTest()
	{
		$cm = new ClassMethods(ClassMethodsTestHelper::class);
		$this->assertTrue($cm->has('case/test'));
		$this->assertInstanceOf(Package::class, $cm->get('case/test'));
	}
}
