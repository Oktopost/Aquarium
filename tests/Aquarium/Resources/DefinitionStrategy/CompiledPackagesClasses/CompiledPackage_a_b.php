<?php
namespace Aquarium\Resources\CompiledScripts;


use Aquarium\Resources\Package\IPackageBuilder;


class CompiledPackage_a_b
{
	public static function get(IPackageBuilder $builder) 
	{
		$builder->style('1');
		$builder->script('2');
		$builder->package('3');
	}
}