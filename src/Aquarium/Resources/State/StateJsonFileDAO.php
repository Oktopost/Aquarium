<?php
namespace Aquarium\Resources\State;


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
		
		$data = '';
		
		/** @var PackageResourceSet[] $objects */
		$objects = Mappers::simple()->getObjects($data, PackageResourceSet::class);
		$this->data = [];
		
		foreach ($objects as $object)
		{
			$this->data[$object->PackageName] = $object;
		}
	}
	
	private function save()
	{
		if (!$this->isModified) return; 
		
		$jsonData = Mappers::simple()->getJson($this->data);
	}
	
	
	public function __destruct()
	{
		$this->save();
	}
	
	
	/**
	 * @param string $name
	 * @return PackageResourceSet
	 */
	public function getPackageState($name)
	{
		
	}
	
	/**
	 * @param PackageResourceSet $set
	 */
	public function setPackageState(PackageResourceSet $set)
	{
		$this->preLoad();
		
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