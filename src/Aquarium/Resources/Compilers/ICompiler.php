<?php
namespace Aquarium\Resources\Compilers;


interface ICompiler
{
	/**
	 * @return static
	 */
	public function addTimestamp();
}