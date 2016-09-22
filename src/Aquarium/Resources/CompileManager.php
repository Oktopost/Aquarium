<?php
namespace Aquarium\Resources;


use Aquarium\Resources\Utils\FileSystem;
use Aquarium\Resources\Compilers\Gulp\GulpException;
use Aquarium\Resources\Compilation\Utils;


class CompileManager
{
	use \Objection\TStaticClass;
	
	
	private static function prepare()
	{
		$fs = new FileSystem();
		
		Config::instance()->log()->logMessage('Clearing: ' . Config::instance()->directories()->PhpTargetDir);
		$fs->deleteFilesByFilter(Config::instance()->directories()->PhpTargetDir, Utils::PACKAGE_CLASS_NAME_PREFIX);
	}
	
	
	/**
	 * @return bool
	 */
	public static function compile()
	{
		$compiler = Config::instance()->compiler();
		
		try 
		{
			self::prepare();
			
			Config::instance()->log()->logMessage('Starting compilation');
			
			$startTime = round(microtime(true), 4);
			$compiler->compile();
			$runTime = round(abs(round(microtime(true), 4) - $startTime), 4);
			
			Config::instance()->log()->logMessage("Complete in $runTime seconds");
		}
		catch (GulpException $e)
		{
			return false;
		}
		catch (\Exception $e)
		{
			Config::instance()->log()->logException($e, 'Unexpected error encounter during compilation');
			return false;
		}
		
		return true;
	}
}