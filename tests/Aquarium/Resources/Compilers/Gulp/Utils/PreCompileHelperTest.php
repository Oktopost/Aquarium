<?php
namespace Aquarium\Resources\Compilers\Gulp\Utils;


use Aquarium\Resources\Modules\Utils\ResourceMap;
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
		$setup = (new PreCompileHelper())->getRecompileTargets(new ResourceMap(), []);
		
		$this->assertEmpty($setup->CompileTarget->get());
		$this->assertEmpty($setup->Unchanged->get());
	}
	
	public function test_getRecompileTargets_NoModifiedObjects_AllResourcesMarkedAsUnchanged()
	{
		$setup = (new PreCompileHelper())->getRecompileTargets(
			(new ResourceMap())->map('a', 'b'), []);
		
		$this->assertEmpty($setup->CompileTarget->get());
		$this->assertEquals(['b'], $setup->Unchanged->get());
	}
	
	public function test_getRecompileTargets_SingleSourceModified_SourceMarkedForRecompile()
	{
		$setup = (new PreCompileHelper())->getRecompileTargets(
			(new ResourceMap())->map('a', 'b'), ['b']);
		
		$this->assertEquals(['a'], $setup->CompileTarget->get());
		$this->assertEmpty($setup->Unchanged->get());
	}
	
	public function test_getRecompileTargets_SingleSourceModifiedWithNumberOfResources_SourcesMarkedForRecompile()
	{
		$setup = (new PreCompileHelper())->getRecompileTargets(
			(new ResourceMap())->map(['a', 'n'], 'b'), ['b']);
		
		$this->assertEquals(['a', 'n'], $setup->CompileTarget->get());
		$this->assertEmpty($setup->Unchanged->get());
	}
	
	public function test_getRecompileTargets_HaveUnmodifiedAndModified()
	{
		$setup = (new PreCompileHelper())->getRecompileTargets(
			(new ResourceMap())->map('a', 'b')->map('c', 'd'), ['b']);
		
		$this->assertEquals(['a'], $setup->CompileTarget->get());
		$this->assertEquals(['d'], $setup->Unchanged->get());
	}
	
	public function test_getRecompileTargets_ModifiedTargetRequestRecompileOfFileWithUnmodifiedTarget()
	{
		$setup = (new PreCompileHelper())->getRecompileTargets(
			(new ResourceMap())->map('a', 'modified')->map('a', 'unmodified'), ['modified']);
		
		$this->assertEquals(['a'], $setup->CompileTarget->get());
		$this->assertEmpty($setup->Unchanged->get());
	}
	
	public function test_getRecompileTargets_ModifiedFileAffectsChainOfFiles()
	{
		$setup = (new PreCompileHelper())->getRecompileTargets(
			(new ResourceMap())
				->map('a', 'modified')
				->map(['a', 'n'], 'b') 
				->map(['n', 'd'], 'c') 
				->map('d', 'last'), 
			['modified']);
		
		$this->assertEquals(['a', 'n', 'd'], $setup->CompileTarget->get());
		$this->assertEmpty($setup->Unchanged->get());
	}
	
	public function test_getRecompileTargets_ModifiedChainNotAffectOthers()
	{
		$setup = (new PreCompileHelper())->getRecompileTargets(
			(new ResourceMap())
				->map('a', 'modified')
				->map(['a', 'n'], 'b')
				->map(['mm', 'mn'], 'unmodified') 
				->map(['n', 'd'], 'c'), 
			['modified']);
		
		$this->assertEquals(['a', 'n', 'd'], $setup->CompileTarget->get());
		$this->assertEquals(['unmodified'], $setup->Unchanged->get());
	}
	
	public function test_getRecompileTargets_SetupReturned()
	{
		$this->assertInstanceOf(CompilerSetup::class, (new PreCompileHelper())->getRecompileTargets(new ResourceMap(), []));
	}
}