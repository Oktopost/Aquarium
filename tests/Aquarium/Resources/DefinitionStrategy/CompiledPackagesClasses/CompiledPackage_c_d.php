<?php
namespace Aquarium\Resources\CompiledScripts;


use Aquarium\Resources\Package\IBuilder;


class CompiledPackage_c_d
{
	public static function get(IBuilder $builder) 
	{
		$builder->style('a');
		$builder->script('b');
		$builder->package('c');
	}
}