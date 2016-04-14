<?php
namespace Aquarium\Resources\CompiledScripts;


use Aquarium\Resources\Package\IBuilder;


class CompiledPackage_a_b
{
	public static function get(IBuilder $builder) 
	{
		$builder->style('1');
		$builder->script('2');
		$builder->package('3');
	}
}