<?php
namespace Aquarium\Resources\Compilers\Gulp;


class GulpCompileConfigTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|IGulpAction
	 */
	private function mockGulpAction()
	{
		return $this->getMock(IGulpAction::class);
	}
	
	
	public function test_addStyleAction_SingleElement()
	{
		$c = new GulpCompileConfig();
		$a1 = $this->mockGulpAction();
		$a2 = $this->mockGulpAction();
		
		$c->addStyleAction($a1);
		$c->addStyleAction($a2);
		
		$this->assertEquals([$a1, $a2], $c->StyleActions);
	}
	
	public function test_addStyleAction_ArrayOfElements()
	{
		$c = new GulpCompileConfig();
		$a1 = $this->mockGulpAction();
		$a2 = $this->mockGulpAction();
		$a3 = $this->mockGulpAction();
		
		$c->addStyleAction($a1);
		$c->addStyleAction([$a2, $a3]);
		
		$this->assertEquals([$a1, $a2, $a3], $c->StyleActions);
	}
	
	
	public function test_addScriptAction_SingleElement()
	{
		$c = new GulpCompileConfig();
		$a1 = $this->mockGulpAction();
		$a2 = $this->mockGulpAction();
		
		$c->addScriptAction($a1);
		$c->addScriptAction($a2);
		
		$this->assertEquals([$a1, $a2], $c->ScriptActions);
	}
	
	public function test_addScriptAction_ArrayOfElements()
	{
		$c = new GulpCompileConfig();
		$a1 = $this->mockGulpAction();
		$a2 = $this->mockGulpAction();
		$a3 = $this->mockGulpAction();
		
		$c->addScriptAction($a1);
		$c->addScriptAction([$a2, $a3]);
		
		$this->assertEquals([$a1, $a2, $a3], $c->ScriptActions);
	}
}