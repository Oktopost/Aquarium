<?php
namespace Aquarium\Resources\Compilers\Gulp\CompileConfig;


use Aquarium\Resources\Compilers\Gulp\Actions\ScssAction;
use Aquarium\Resources\Compilers\Gulp\Actions\JsMinifyAction;
use Aquarium\Resources\Compilers\Gulp\Actions\CssMinifyAction;
use Aquarium\Resources\Compilers\Gulp\Actions\ConcatenateAction;


class ActionChainBuilderTest extends \PHPUnit_Framework_TestCase
{
	public function test_ReturnSelf() 
	{
		$c = new ActionChainBuilder();
		
		$this->assertSame($c, $c->concatenate('a'));
		$this->assertSame($c, $c->concatenate());
		$this->assertSame($c, $c->jsmin());
		$this->assertSame($c, $c->cssmin());
		$this->assertSame($c, $c->sass());
	}
	
	
	public function test_sanity() 
	{
		$c = new ActionChainBuilder();
		
		$c->concatenate('a');
		$c->concatenate();
		$c->jsmin();
		$c->cssmin();
		$c->sass();
		
		$collection = $c->getCollection();
		
		$this->assertInstanceOf(ConcatenateAction::class, $collection[0]);
		$this->assertInstanceOf(ConcatenateAction::class, $collection[1]);
		$this->assertInstanceOf(JsMinifyAction::class, $collection[2]);
		$this->assertInstanceOf(CssMinifyAction::class, $collection[3]);
		$this->assertInstanceOf(ScssAction::class, $collection[4]);
	}
}