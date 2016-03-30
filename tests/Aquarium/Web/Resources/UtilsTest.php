<?php
namespace Aquarium\Web\Resources;


class UtilsTest extends \PHPUnit_Framework_TestCase {
	
	public function test_isValidPackageName_InvalidValues() {
		$this->assertFalse(Utils::isValidPackageName('/asd'));
		$this->assertFalse(Utils::isValidPackageName('asd/'));
		$this->assertFalse(Utils::isValidPackageName('a.a'));
		$this->assertFalse(Utils::isValidPackageName('a,a'));
		$this->assertFalse(Utils::isValidPackageName('a a'));
	}
	
	public function test_isValidPackageName_ValidValues() {
		$this->assertTrue(Utils::isValidPackageName('a'));
		$this->assertTrue(Utils::isValidPackageName('a/a'));
		$this->assertTrue(Utils::isValidPackageName('a/a/a'));
		$this->assertTrue(Utils::isValidPackageName('0'));
		$this->assertTrue(Utils::isValidPackageName('-'));
		$this->assertTrue(Utils::isValidPackageName('_'));
		$this->assertTrue(Utils::isValidPackageName('abc/0123/def'));
	}
}
