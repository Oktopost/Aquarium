<?php
namespace Aquarium\Resources\Compilers\Gulp;


use Aquarium\Resources\Package;
use Aquarium\Resources\Utils\ResourceCollection;


class CompilerSetupTest extends \PHPUnit_Framework_TestCase
{
	public function test_sanity()
	{
		$p = new Package('a');
		$c = new CompilerSetup($p);
		
		$this->assertSame($p, $c->Package);
		$this->assertInstanceOf(ResourceCollection::class, $c->CompileTarget);
	}
	
	
	public function test_hasToCompile()
	{
		$c = new CompilerSetup(new Package('a'));
		
		$this->assertFalse($c->hasToCompile());
		
		$c->CompileTarget->add('a');
		
		$this->assertTrue($c->hasToCompile());
	}
}