<?php
namespace Aquarium\Resources\Compilers\Gulp\Utils;


use Aquarium\Resources\Config;
use Aquarium\Resources\Package;
use Aquarium\Resources\Compilers\Gulp\Process\ICompileHelper;


class CompileHelper implements ICompileHelper
{
	/**
	 * Remove any files from the package directory.
	 * @param Package $p
	 */
	public function cleanDirectory(Package $p)
	{
		$targetDir = Config::instance()->directories()->CompiledResourcesDir;
		$packageDir = $targetDir . DIRECTORY_SEPARATOR . $p->getName('_');
		
		$allFiles = array_merge($p->Scripts->get(), $p->Styles->get(), $p->Views->get());
		
		foreach (glob($packageDir . '/*') as $path)
		{
			if (!is_file($path)) continue;
			
			if (in_array($path, $allFiles)) continue;
			
			unlink($path);
		}
	}
}