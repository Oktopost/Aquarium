<?php
namespace Aquarium\Resources\Compilers\Gulp\Utils;


use Aquarium\Resources\Package;
use Aquarium\Resources\Utils\ResourceMap;
use Aquarium\Resources\Compilers\Gulp\CompilerSetup;


class PreCompileHelperTest extends \PHPUnit_Framework_TestCase
{
	public function test_getTimestamps_Sanity()
	{
		$result = (new PreCompileHelper())->getTimestamps([__FILE__, __DIR__ . '/notfound']);
		
		$this->assertEquals(
			[
				__FILE__				=> filemtime(__FILE__),
				__DIR__ . '/notfound'	=> 0
			],
			$result);
	}
	
	
	public function test_getRecompileTargets_NoData_ReturnEmptySetup()
	{
		$setup = (new PreCompileHelper())->getRecompileTargets(new Package('a'), new ResourceMap());
		
		$this->assertEmpty($setup->CompileTarget->get());
	}
	
	public function test_getRecompileTargets_SetupReturned()
	{
		$this->assertInstanceOf(
			CompilerSetup::class, 
			(new PreCompileHelper())->getRecompileTargets(new Package('a'), new ResourceMap()));
	}
	
	public function test_getRecompileTargets_ValidatePAckagePassedToCompiler()
	{
		$p = new Package('a');
		
		$compiler = (new PreCompileHelper())->getRecompileTargets($p, new ResourceMap());
		
		$this->assertSame($p, $compiler->Package);
	}
}