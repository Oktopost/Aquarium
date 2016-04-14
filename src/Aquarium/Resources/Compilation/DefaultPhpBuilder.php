<?php
namespace Aquarium\Resources\Compilation;


use Aquarium\Resources\Config;
use Aquarium\Resources\Package;
use Aquarium\Resources\Package\IBuilder;


class DefaultPhpBuilder implements IPhpBuilder
{
	/**
	 * @param string $packageName
	 * @return resource
	 */
	private function createFile($packageName)
	{
		$filePath = Config::instance()->Directories->PhpTargetDir . DIRECTORY_SEPARATOR . $packageName . '.php';
		$handle = fopen($filePath, 'w');
		
		if (!$handle) 
			throw new \Exception("Failed to open file for writing: [$filePath]");
		
		return $handle;
	}
	
	/**
	 * @param resource $resource
	 * @param \Aquarium\Resources\Package $package
	 */
	private function writePhpFile($resource, Package $package)
	{
		$className = Utils::getClassName($package->Name);
		
		// Write head.
		fprintf($resource, <<<TAG
<?php
namespace %s;

class %s 
{
	public static function get(\%s \$b) 
	{

TAG
			, 
			Utils::COMPILED_CLASSES_NAMESPACE, 
			$className, 
			IBuilder::class);
		
		// Write main function body.
		foreach ($package->Requires as $required)
		{
			fprintf($resource, "\t\t\$b->package('%s');%s", $required, PHP_EOL);
		}
		
		foreach ($package->Styles as $style)
		{
			fprintf($resource, "\t\t\$b->style('%s');%s", $style, PHP_EOL);
		}
		
		foreach ($package->Scripts as $script)
		{
			fprintf($resource, "\t\t\$b->script('%s');%s", $script, PHP_EOL);
		}
		
		// Write tail.
		fprintf($resource, <<<TAG
	}
}
TAG
		); 
	}
	
	
	/**
	 * @param \Aquarium\Resources\Package $package
	 */
	public function buildPhpFile(Package $package)
	{
		$className = Utils::getClassName($package->Name);
		$resource = $this->createFile($className);
		
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