<?php
namespace Aquarium\Web\Resources\Compilation;


interface ICompiler
{
	/**
	 * @throws \Exception
	 */
	public function compile();
}