<?php
namespace Aquarium\Web\Resources;


use Aquarium\Web\Resources\ConstructorStrategy\SimpleConstructor;


class ConfigDefaults
{
	use \Objection\TStaticClass;
	
	
	public static function set() 
	{
		if (is_null(Config::instance()->PackageConstructor))
			Config::instance()->PackageConstructor = new SimpleConstructor();
	}
}