<?php
namespace Aquarium\Resources\Compilation;


use Aquarium\Resources\Config;
use Aquarium\Resources\Package;
use Aquarium\Resources\Package\IPackageBuilder;


class DefaultPhpBuilder implements IPhpBuilder
{
	/**
	 * @param string $packageName
	 * @return resource
	 */
	private function createFile($packageName)
	{
		$filePath = Config::instance()->directories()->PhpTargetDir . DIRECTORY_SEPARATOR . $packageName . '.php';
		$handle = fopen($filePath, 'w');
		
		if (!$handle) 
			throw new \Exception("Failed to open file for writing: [$filePath]");
		
		return $handle;
	}
	
	/**
	 * @param resource $resource
	 * @param Package $package
	 */
	private function startFile($resource, Package $package)
	{
		$className = Utils::getClassName($package->Name);
		
		$data = <<<TAG
<?php
namespace %s;

class %s 
{
	public static function get(\%s \$b) 
	{

TAG
		;
		
		fprintf(
			$resource,
			$data,
			Utils::COMPILED_CLASSES_NAMESPACE,
			$className,
			IPackageBuilder::class);
	}
	
	/**
	 * @param resource $resource
	 * @param Package $package
	 */
	private function writePackage($resource, Package $package)
	{
		foreach ($package->Requires as $required)
		{
			fprintf($resource, "\t\t\$b->package('%s');%s", $required, PHP_EOL);
		}
		
		foreach ($package->Inscribed as $inscribed)
		{
			fprintf($resource, "\t\t\$b->inscribe('%s');%s", $inscribed, PHP_EOL);
		}
		
		foreach ($package->Styles as $style)
		{
			fprintf($resource, "\t\t\$b->style('%s');%s", $style, PHP_EOL);
		}
		
		foreach ($package->Scripts as $script)
		{
			fprintf($resource, "\t\t\$b->script('%s');%s", $script, PHP_EOL);
		}
		
		foreach ($package->Views as $script)
		{
			fprintf($resource, "\t\t\$b->script('%s');%s", $script, PHP_EOL);
		}
	}
	
	/**
	 * @param resource $resource
	 */
	private function endFile($resource)
	{
		$data = <<<TAG
	}
}
TAG
		;
		
		fprintf($resource, $data); 
	}
	
	/**
	 * @param resource $resource
	 * @param Package $package
	 */
	private function writePhpFile($resource, Package $package)
	{
		$this->startFile($resource, $package);
		$this->writePackage($resource, $package);
		$this->endFile($resource);
	}
	
	
	/**
	 * @param \Aquarium\Resources\Package $package
	 */
	public function buildPhpFile(Package $package)
	{
		$className = Utils::getClassName($package->Name);
		$resource = $this->createFile($className);
		
		Config::instance()->directories()->truncateResourcesToPublicDir($package);
		
		try 
		{
			$this->writePhpFile($resource, $package);
		}
		finally
		{
			fclose($resource);
		}
	}
}