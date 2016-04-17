<?php
namespace Aquarium\FileSystem;


class File
{
	private $type;
	private $name;
	private $path;
	private $directory;
	
	/** @var array */
	private $pathParts;
	
	
	/**
	 * Extract file path, bare nam
	 */
	private function extractInfoFromPath()
	{
		$data = pathinfo($this->path);
		
		$this->type = (isset($data['extension']) ? $data['extension'] : '');
		$this->name = (isset($data['filename']) ? $data['filename'] : '');
		$this->directory = (isset($data['dirname']) ? $data['dirname'] : '');
	}
	
	/**
	 * Extract path parts, name and type from path.
	 */
	private function extractPathPartsFromInfo()
	{
		$this->pathParts = Utils::pathAsArray($this->directory);
		$this->pathParts[] = $this->getFileName();
	}
	
	private function updateName()
	{
		$lastPart = count($this->pathParts) - 1;
		$this->pathParts[$lastPart] = $this->name . ($this->type ? ".{$this->type}" : '');
		$this->extractPathPartsFromInfo();
	}
	
	
	/**
	 * @param string $path
	 */
	public function __construct($path)
	{
		$this->setPath($path);
	}
	
	
	/**
	 * @param string $path
	 */
	public function setPath($path) 
	{
		if (!$path)
			throw new \Exception("Path can not be empty");
		
		$this->path = Utils::validatePath($path);
		
		$this->extractInfoFromPath();
		$this->extractPathPartsFromInfo();
	}
	
	/**
	 * @return string
	 */
	public function getPath() 
	{
		return $this->path;
	}
	
	/**
	 * @param array $path
	 */
	public function setPathAsArray($path)
	{
		$this->setPath(implode(DIRECTORY_SEPARATOR, $path));
	}
	
	/**
	 * @return array
	 */
	public function getPathAsArray()
	{
		return $this->pathParts;
	}
	
	/**
	 * @return bool
	 */
	public function hasType()
	{
		return (bool)$this->type;
	}
	
	/**
	 * @param string|mixed $default
	 * @return string|mixed
	 */
	public function getType($default = '')
	{
		return $this->type ?: $default;
	}
	
	/**
	 * @param string $type
	 */
	public function setType($type)
	{
		$this->type = $type;
		$this->updateName();
	}
	
	/**
	 * Set file name without altering it's type.
	 * @param string $name
	 */
	public function setBareFileName($name)
	{
		$this->name = $name;
		$this->updateName();
	}
	
	/**
	 * Get file name without it's type.
	 * @return string
	 */
	public function getBareFileName()
	{
		return $this->name;
	}
	
	/**
	 * Get full file name with type.
	 * @return string
	 */
	public function getFileName()
	{
		if (!$this->type) return $this->name;
		
		return $this->name . '.' . $this->type;
	}
	
	/**
	 * @return bool
	 */
	public function isExists()
	{
		return file_exists($this->path);
	}
	
	/**
	 * @return bool
	 */
	public function isFullPath()
	{
		return ($this->pathParts[0] != '.');
	}
	
	/**
	 * @param string $path
	 * @param bool $ignoreIfFull If true do nothing if path to the file is already full; otherwise throw exception
	 * @throws \Exception
	 */
	public function setFullPath($path, $ignoreIfFull = true) 
	{
		if ($this->pathParts[0] !== '.')
		{
			if ($ignoreIfFull) return;
			
			throw new \Exception("Path to file '$this->path' is already full. When trying to set full path '$path'");
		}
		
		$this->setPath($path . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, array_splice($this->pathParts, 1)));
	}
}