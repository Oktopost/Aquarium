<?php
namespace Aquarium\Resources\Modules;


use Aquarium\Resources\Config;
use Aquarium\Resources\Manager;


class ConfigDefaults
{
	use \Objection\TStaticClass;
	
	
	public static function set() 
	{
		if (is_null(Config::instance()->Provider))
			Config::instance()->Provider = new Manager();
	}
}