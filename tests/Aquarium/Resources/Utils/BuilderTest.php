<?php
namespace Aquarium\Resources\Utils;


use Aquarium\Resources\Package;


class BuilderTest extends \PHPUnit_Framework_TestCase
{
	public function test_ReturnSelf()
	{
		$b = new Builder();
		
		$this->assertSame($b, $b->setup(new Package('a')));
		$this->assertSame($b, $b->package('n'));
		$this->assertSame($b, $b->script('b'));
		$this->assertSame($b, $b->style('c'));
	}
	
	
	public function test_sanity()
	{
		$p = new Package('a');
		$b = new Builder();
		$b->setup($p);
		
		$b->package('n');
		$b->style('s');
		$b->script('b');
		
		$this->assertEquals(['n'], $p->Requires->get());
		$this->assertEquals(['s'], $p->Styles->get());
		$this->assertEquals(['b'], $p->Scripts->get());
	}
}