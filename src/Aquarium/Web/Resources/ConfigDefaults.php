<?php
namespace Aquarium\Web\Resources;


class ConfigDefaults
{
	use \Objection\TStaticClass;
	
	
	public static function set() 
	{
		if (is_null(Config::instance()->Provider))
			Config::instance()->Provider = new Manager();
	}
}