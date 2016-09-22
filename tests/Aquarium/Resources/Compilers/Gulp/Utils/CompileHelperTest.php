<?php
namespace Aquarium\Resources\Compilers\Gulp\Utils;


use Aquarium\Resources\Config;
use Aquarium\Resources\Package;


class CompileHelperTest extends \PHPUnit_Framework_TestCase
{
	private $targetPath		= __DIR__ . '/CompilerHelper';
	private $packagePath	= __DIR__ . '/CompilerHelper/package_name';
	
	
	public function test_cleanDirectory() 
	{
		touch($this->packagePath . '/inp_a');
		touch($this->packagePath . '/inp_b');
		touch($this->packagePath . '/notin_a');
		
		// Sanity check
		$this->assertFileExists($this->packagePath . '/inp_a');
		$this->assertFileExists($this->packagePath . '/inp_b');
		$this->assertFileExists($this->packagePath . '/notin_a');
		
		
		$package = new Package('package/name');
		$package->Scripts->add($this->packagePath . '/inp_a');
		$package->Styles->add($this->packagePath . '/inp_b');
		
		Config::instance()->directories()->CompiledResourcesDir = $this->targetPath;
		
		(new CompileHelper())->cleanDirectory($package);
		
		
		$this->assertFileExists($this->packagePath . '/inp_a');
		$this->assertFileExists($this->packagePath . '/inp_b');
		$this->assertFileExists($this->packagePath . '/Dir/.keep');
		$this->assertFileNotExists($this->packagePath . '/notin_a');
	}
}