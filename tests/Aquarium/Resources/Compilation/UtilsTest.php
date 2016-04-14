<?php
namespace Aquarium\Resources\Compilation;


use Aquarium\Resources\Config;
use Aquarium\Resources\Package;


class UtilsTest extends \PHPUnit_Framework_TestCase
{
	public function test_getClassName_NameAsString()
	{
		$this->assertEquals(Utils::PACKAGE_CLASS_NAME_PREFIX . 'a_b', Utils::getClassName('a/b'));
	}
	
	public function test_getClassName_PackagePasses()
	{
		$this->assertEquals(Utils::PACKAGE_CLASS_NAME_PREFIX . 'a_b', Utils::getClassName(new Package('a/b')));
	}
	
	
	public function test_getFullClassName_NameAsString()
	{
		$this->assertEquals(
			Utils::COMPILED_CLASSES_NAMESPACE . '\\' . Utils::PACKAGE_CLASS_NAME_PREFIX . 'a_b', 
			Utils::getFullClassName('a/b'));
	}
	
	public function test_getFullClassName_PackagePasses()
	{
		$this->assertEquals(
			Utils::COMPILED_CLASSES_NAMESPACE . '\\' . Utils::PACKAGE_CLASS_NAME_PREFIX . 'a_b', 
			Utils::getFullClassName(new Package('a/b')));
	}
	
	
	public function test_getClassPath_StringPassed()
	{
		$sep = DIRECTORY_SEPARATOR;
		Config::instance()->Directories->PhpTargetDir = "{$sep}a{$sep}b";
		
		$this->assertEquals(
			"{$sep}a{$sep}b{$sep}" . Utils::PACKAGE_CLASS_NAME_PREFIX . 'a_b.php',
			Utils::getClassPath('a/b'));
	}
	
	public function test_getClassPath_PackagePassed()
	{
		$sep = DIRECTORY_SEPARATOR;
		Config::instance()->Directories->PhpTargetDir = "{$sep}a{$sep}b";
		
		$this->assertEquals(
			"{$sep}a{$sep}b{$sep}" . Utils::PACKAGE_CLASS_NAME_PREFIX . 'a_b.php',
			Utils::getClassPath(new Package('a/b')));
	}
}
