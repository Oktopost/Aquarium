<?php
namespace Aquarium\Resources\Utils;


use Aquarium\Resources\Package;


class PackageBuilderTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		PackageBuilder::setTestMode(true);
	}
	
	public function test_ReturnSelf()
	{
		$b = new PackageBuilder();
		
		$this->assertSame($b, $b->setup(new Package('a')));
		$this->assertSame($b, $b->package('n'));
		$this->assertSame($b, $b->script('b'));
		$this->assertSame($b, $b->style('c'));
		$this->assertSame($b, $b->inscribe('1'));
	}
	
	
	public function test_sanity()
	{
		$p = new Package('a');
		$b = new PackageBuilder();
		$b->setup($p);
		
		$b->package('n');
		$b->style('s');
		$b->script('b');
		$b->inscribe('1');
		
		$this->assertEquals(['n'], $p->Requires->get());
		$this->assertEquals(['s'], $p->Styles->get());
		$this->assertEquals(['b'], $p->Scripts->get());
		$this->assertEquals(['1'], $p->Inscribed->get());
	}
}