<?php
namespace Aquarium\Web\Resources\Utils;


class PackageUtilsTest extends \PHPUnit_Framework_TestCase 
{
	public function test_isValidPackageName_InvalidValues() 
	{
		$this->assertFalse(PackageUtils::isValidPackageName('/asd'));
		$this->assertFalse(PackageUtils::isValidPackageName('asd/'));
		$this->assertFalse(PackageUtils::isValidPackageName('a.a'));
		$this->assertFalse(PackageUtils::isValidPackageName('a,a'));
		$this->assertFalse(PackageUtils::isValidPackageName('a a'));
	}
	
	public function test_isValidPackageName_ValidValues()
	{
		$this->assertTrue(PackageUtils::isValidPackageName('a'));
		$this->assertTrue(PackageUtils::isValidPackageName('a/a'));
		$this->assertTrue(PackageUtils::isValidPackageName('a/a/a'));
		$this->assertTrue(PackageUtils::isValidPackageName('0'));
		$this->assertTrue(PackageUtils::isValidPackageName('-'));
		$this->assertTrue(PackageUtils::isValidPackageName('_'));
		$this->assertTrue(PackageUtils::isValidPackageName('abc/0123/def'));
	}
}
