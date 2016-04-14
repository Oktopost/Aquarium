<?php
namespace Aquarium\Resources\Compilers\Gulp;


class GulpExceptionTest extends \PHPUnit_Framework_TestCase
{
	public function test_sanity()
	{
		$e = new GulpException(12, "abc");
		$this->assertEquals(12, $e->getCode());
		$this->assertEquals("abc", $e->getOutput());
	}
}
