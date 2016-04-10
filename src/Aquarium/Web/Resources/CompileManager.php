<?php
namespace Aquarium\Web\Resources;


use Aquarium\Web\Resources\Compilation\Utils;
use Aquarium\Web\Resources\Utils\FileSystem;


class CompileManager
{
	use \Objection\TStaticClass;
	
	
	private function prepare()
	{
		$fs = new FileSystem();
		
		echo "Clearing: " . Config::instance()->Directories->PhpTargetDir . PHP_EOL;
		$fs->deleteFilesByPrefix(Config::instance()->Directories->PhpTargetDir, Utils::PACKAGE_CLASS_NAME_PREFIX);
		
		echo "Clearing: " . Config::instance()->Directories->ResourcesTargetDir . PHP_EOL;
		$fs->deleteFilesByPrefix(Config::instance()->Directories->ResourcesTargetDir, Utils::FILE_NAME_PREFIX);
	}
	
	
	public static function compile()
	{
		try 
		{
			self::prepare();
			
			echo "Compiling..." . PHP_EOL;
			Config::instance()->Compiler->compile();
		}
		catch (\Exception $e)
		{
			echo "Error encounter during compilation: " . PHP_EOL;
			echo $e->getMessage() . PHP_EOL;
			echo $e->getTraceAsString() . PHP_EOL;
			echo "Aborting compilation!" . PHP_EOL;
		}
	}
}