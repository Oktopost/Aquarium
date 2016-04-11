<?php
namespace Aquarium\Resources\Modules\Compilers\Gulp;


class ShellTest extends \PHPUnit_Framework_TestCase
{
	public function test_success()
	{
		$s = new Shell();
		
		$result = $s->execute('echo Works; echo Line 2', $output);
		
		$this->assertEquals(0, $result);
		$this->assertEquals('Works' . PHP_EOL . 'Line 2', $output);
	}
	
	public function test_failure()
	{
		$s = new Shell();
		
		$result = $s->execute('exit 4', $output);
		
		$this->assertEquals(4, $result);
	}
}
