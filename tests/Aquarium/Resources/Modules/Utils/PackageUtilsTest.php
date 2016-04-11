<?php
namespace Aquarium\Resources\Modules\Utils;


use Aquarium\Resources\Package;


class PackageUtilsTest extends \PHPUnit_Framework_TestCase 
{
	public function test_isValidPackageName_InvalidValues() 
	{
		$this->assertFalse(Package::isValidPackageName('/asd'));
		$this->assertFalse(Package::isValidPackageName('asd/'));
		$this->assertFalse(Package::isValidPackageName('a.a'));
		$this->assertFalse(Package::isValidPackageName('a,a'));
		$this->assertFalse(Package::isValidPackageName('a a'));
	}
	
	public function test_isValidPackageName_ValidValues()
	{
		$this->assertTrue(Package::isValidPackageName('a'));
		$this->assertTrue(Package::isValidPackageName('a/a'));
		$this->assertTrue(Package::isValidPackageName('a/a/a'));
		$this->assertTrue(Package::isValidPackageName('0'));
		$this->assertTrue(Package::isValidPackageName('abc/0123/def'));
	}
}
