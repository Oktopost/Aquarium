<?php
namespace Aquarium\Resources\State;


use Aquarium\Resources\Config;
use Aquarium\Resources\Base\State\IStateDAO;

use Objection\Mapper;
use Objection\Mappers;


class StateJsonFileDAO implements IStateDAO
{
	/** @var PackageResourceSet[] */
	private $data = null;
	
	private $filePath = '';
	private $isModified = false;
	
	
	private function preLoad()
	{
		if (!is_null($this->data)) return;
		
		if (!$this->filePath) 
		{
			$this->filePath = Config::instance()->directories()->StateFile;
		}
		
		if (!file_exists($this->filePath))
		{
			$this->data = [];
			return;
		}
		
		$data = file_get_contents($this->filePath);
		
		if (!$data) $data = '[]';
		
		/** @var PackageResourceSet[] $objects */
		$objects = Mappers::simple()->getObjects(json_decode($data), PackageResourceSet::class);
		$this->data = [];
		
		foreach ($objects as $object)
		{
			$object->setRootPath();
			$this->data[$object->PackageName] = $object;
		}
	}
	
	private function save()
	{
		if (!$this->isModified) return; 
		
		foreach ($this->data as $item)
		{
			foreach ($item->Source->Files as $file)
			{
				if ($file->isExists())
				{
					$file->populateMD5IfEmpty();
					$file->populateSizeIfEmpty();
				}
			}
			
			foreach ($item->Target->Files as $file)
			{
				if ($file->isExists())
				{
					$file->populateMD5IfEmpty();
					$file->populateSizeIfEmpty();
				}
			}
		}
		
		$jsonData = Mappers::simple()->getJson($this->data, JSON_PRETTY_PRINT);
		
		if (file_put_contents($this->filePath, $jsonData, LOCK_EX) === false)
		{
			throw new \Exception('Failed to write to the state file: ' . $this->filePath);
		}
	}
	
	
	public function __destruct()
	{
		$this->save();
	}
	
	
	/**
	 * @param string $name
	 * @return PackageResourceSet|null
	 */
	public function getPackageState($name)
	{
		$this->preLoad();
		
		return (isset($this->data[$name]) ? $this->data[$name] : null); 
	}
	
	/**
	 * @param PackageResourceSet $set
	 */
	public function setPackageState(PackageResourceSet $set)
	{
		$this->preLoad();
		
		$this->isModified = true;
		$this->data[$set->PackageName] = $set;
	}
	
	/**
	 * @return array
	 */
	public function getPackageNames()
	{
		$this->preLoad();
		
		return array_keys($this->data);
	}
}