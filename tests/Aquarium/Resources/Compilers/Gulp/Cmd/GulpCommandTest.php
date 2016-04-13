<?php
namespace Aquarium\Resources\Compilers\Gulp\Cmd;


use Aquarium\Resources\Compilers\Gulp\IShell;


class GulpCommandTest extends \PHPUnit_Framework_TestCase
{
	
	/**
	 * @param int $return
	 * @return \PHPUnit_Framework_MockObject_MockObject|IShell
	 */
	private function mockEmptyIShell($return = 0) 
	{
		$shell = $this->getMock(IShell::class);
		$shell->method('execute')->willReturn($return);
		return $shell;
	}
	
	/**
	 * @param string $command
	 * @param int $return
	 * @return \PHPUnit_Framework_MockObject_MockObject|IShell
	 */
	private function mockIShell($command, $return = 0) 
	{
		$shell = $this->getMock(IShell::class);
		$shell->expects($this->once())->method('execute')->with($command, $this->anything())->willReturn($return);
		return $shell;
	}
	
	
	public function test_PathUsed()
	{
		$path = '/path/to';
		$shell = $this->mockIShell("cd $path && gulp a");
		
		(new GulpCommand())
			->setGulpPath($path)
			->setAction('a')
			->execute($shell);
	}
	
	public function test_ActionUsed()
	{
		$shell = $this->mockIShell("cd a && gulp action");
		
		(new GulpCommand())
			->setGulpPath('a')
			->setAction('action')
			->execute($shell);
	}
	
	public function test_SingleArgumentUsed()
	{
		$shell = $this->mockIShell("cd a && gulp a arg_a");
		
		(new GulpCommand())
			->setGulpPath('a')
			->setAction('a')
			->setArg('arg_a')
			->execute($shell);
	}
	
	public function test_ArgumentWithValueUsed()
	{
		$shell = $this->mockIShell("cd a && gulp a arg_a 2");
		
		(new GulpCommand())
			->setGulpPath('a')
			->setAction('a')
			->setArg('arg_a', 2)
			->execute($shell);
	}
	
	public function test_ObjectArgument()
	{
		$shell = $this->mockIShell('cd a && gulp a arg_a {\\"a\\":\\"b\\"}');
		
		(new GulpCommand())
			->setGulpPath('a')
			->setAction('a')
			->setArg('arg_a', ["a" => "b"])
			->execute($shell);
	}
	
	public function test_NumberOfArguments()
	{
		$shell = $this->mockIShell('cd a && gulp a arg_a {\\"a\\":\\"b\\"} arg_b');
		
		(new GulpCommand())
			->setGulpPath('a')
			->setAction('a')
			->setArg('arg_a', ["a" => "b"])
			->setArg('arg_b')
			->execute($shell);
	}
	
	/**
	 * @expectedException \Aquarium\Resources\Compilers\Gulp\GulpException
	 */
	public function test_ResultNot0_ErrorThrown()
	{
		$shell = $this->mockEmptyIShell(13);
		
		(new GulpCommand())
			->setGulpPath('a')
			->setAction('a')
			->execute($shell);
	}
}