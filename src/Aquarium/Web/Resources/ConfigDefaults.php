<?php
namespace Aquarium\Web\Resources;


use Aquarium\Web\Resources\ConstructorStrategy\DevConstructor;


class ConfigDefaults
{
	use \Objection\TStaticClass;
	
	
	public static function set() 
	{
		if (is_null(Config::instance()->PackageConstructor))
			Config::instance()->PackageConstructor = new DevConstructor();
		
		if (is_null(Config::instance()->Provider))
			Config::instance()->Provider = new Manager();
	}
}