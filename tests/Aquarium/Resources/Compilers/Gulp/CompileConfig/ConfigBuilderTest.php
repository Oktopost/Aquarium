<?php
namespace Aquarium\Resources\Compilers\Gulp\CompileConfig;


use Aquarium\Resources\Compilers\Gulp\Actions\JsMinifyAction;
use Aquarium\Resources\Compilers\Gulp\Actions\ScssAction;
use Aquarium\Resources\Compilers\Gulp\GulpCompileConfig;


class ConfigBuilderTest extends \PHPUnit_Framework_TestCase
{
	public function test_style()
	{
		$this->assertInstanceOf(ActionChainBuilder::class, (new ConfigBuilder())->style());
	}
	
	
	public function test_script()
	{
		$this->assertInstanceOf(ActionChainBuilder::class, (new ConfigBuilder())->script());
	}
	
	
	public function test_addTimestamp_Called_TimestampAdded()
	{
		$b = new ConfigBuilder();
		$b->addTimestamp();
		$this->assertTrue($b->getConfig()->IsAddTimestamp);
	}
	
	public function test_addTimestamp_NotCalled_TimestampNotAdded()
	{
		$this->assertFalse((new ConfigBuilder())->getConfig()->IsAddTimestamp);
	}
	
	
	public function test_getConfig_ReturnsObject()
	{
		$this->assertInstanceOf(GulpCompileConfig::class, (new ConfigBuilder())->getConfig());
	}
	
	public function test_getConfig_StylesAdded()
	{
		$b = new ConfigBuilder();
		$b->style()->sass();
		
		$config = $b->getConfig();
		
		$this->assertInstanceOf(ScssAction::class, $config->StyleActions[0]);
	}
	
	public function test_getConfig_ScriptsAdded()
	{
		$b = new ConfigBuilder();
		$b->script()->jsmin();
		
		$config = $b->getConfig();
		
		$this->assertInstanceOf(JsMinifyAction::class, $config->ScriptActions[0]);
	}
}