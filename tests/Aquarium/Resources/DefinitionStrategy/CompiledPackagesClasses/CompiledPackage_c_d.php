<?php
namespace Aquarium\Resources\CompiledScripts;


use Aquarium\Resources\Package\IPackageBuilder;


class CompiledPackage_c_d
{
	public static function get(IPackageBuilder $builder) 
	{
		$builder->style('a');
		$builder->script('b');
		$builder->package('c');
	}
}