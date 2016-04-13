<?php
namespace Aquarium\Resources\Compilers\Gulp\Process;


use Aquarium\Resources\Package;


interface ICompileHelper
{
	/**
	 * Remove any files from the package directory.
	 * @param Package $p
	 */
	public function cleanDirectory(Package $p);
}