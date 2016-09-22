<?php
namespace Aquarium\Resources;


use Aquarium\Resources\Compilation\Utils;
use Aquarium\Resources\Utils\FileSystem;


class CompileManager
{
	use \Objection\TStaticClass;
	
	
	private static function prepare()
	{
		$fs = new FileSystem();
		
		Config::instance()->Log->info('Clearing: ' . Config::instance()->Directories->PhpTargetDir);
		$fs->deleteFilesByFilter(Config::instance()->Directories->PhpTargetDir, Utils::PACKAGE_CLASS_NAME_PREFIX);
	}
	
	
	public static function compile()
	{
		try 
		{
			self::prepare();
			
			Config::instance()->Log->info('Compiling...');
			Config::instance()->GulpCompiler->compile();
			Config::instance()->Log->info('Complete!');
		}
		catch (\Exception $e)
		{
			Config::instance()->Log->error(
				'Error encounter during compilation', 
				[
					'Error' => $e->getMessage(),
					'Trace' => $e->getTraceAsString()
				]);
		}
	}
}