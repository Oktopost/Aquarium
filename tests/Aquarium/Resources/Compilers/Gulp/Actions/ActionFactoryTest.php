<?php
namespace Aquarium\Resources\Compilers\Gulp\Actions;


class ActionFactoryTest extends \PHPUnit_Framework_TestCase
{
	public function test_sanity() 
	{
		$this->assertInstanceOf(ScssAction::class, (new ActionFactory())->sass());
		$this->assertInstanceOf(CssMinifyAction::class, (new ActionFactory())->cssmin());
		$this->assertInstanceOf(HandleBarAction::class, (new ActionFactory())->handleBar());
		$this->assertInstanceOf(JsMinifyAction::class, (new ActionFactory())->jsmin());
		$this->assertInstanceOf(ConcatenateAction::class, (new ActionFactory())->concatenate());
		$this->assertInstanceOf(ConcatenateAction::class, (new ActionFactory())->concatenate('*'));
	}
}
