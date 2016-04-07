<?php
namespace Aquarium\Web\Resources\Compilation;


use Aquarium\Web\Resources\Config;
use Aquarium\Web\Resources\Package\IBuilder;
use Aquarium\Web\Resources\Package\PackageDefinition;


class DefaultBuilder implements IPhpBuilder
{
	
	/**
	 * @param string $packageName
	 * @return resource
	 */
	private function createFile($packageName)
	{
		$filePath = Config::instance()->Directories->PhpTargetDir . DIRECTORY_SEPARATOR . $packageName . '.php';
		$handle = fopen($filePath, 'W');
		
		if (!$handle) 
			throw new \Exception("Failed to open file for writing: [$filePath]");
		
		return $handle;
	}
	
	/**
	 * @param resource $resource
	 * @param PackageDefinition $definition
	 */
	private function writePhpFile($resource, PackageDefinition $definition)
	{
		$className = Utils::getClassName($definition->Package->Name);
		
		// Write head.
		fprintf($resource, <<<TAG
<?php
namespace %s;

class %s {
	public function get(%s \$b) {
TAG
			,	
			Utils::PACKAGE_CLASS_NAME_PREFIX, 
			$className,
			IBuilder::class);
		
		// Write main function body.
		foreach ($definition->Packages as $package) 
		{
			fprintf($resource, "\t\t\$b->package(%s);%s", $package, PHP_EOL);
		}
		
		foreach ($definition->Styles as $style) 
		{
			fprintf($resource, "\t\t\$b->style(%s);%s", $style, PHP_EOL);
		}
		
		foreach ($definition->Scripts as $script) 
		{
			fprintf($resource, "\t\t\$b->script(%s);%s", $script, PHP_EOL);
		}
		
		// Write tail.
		fprintf($resource, <<<TAG
	}
}
TAG
		); 
	}
	
	
	/**
	 * @param PackageDefinition $definition
	 */
	public function buildPhpFile(PackageDefinition $definition)
	{
		$className = Utils::getClassName($definition->Package->Name);
		$resource = $this->createFile($className);
		
		try 
		{
			$this->writePhpFile($resource, $definition);
		}
		finally
		{
			fclose($resource);
		}
	}
}