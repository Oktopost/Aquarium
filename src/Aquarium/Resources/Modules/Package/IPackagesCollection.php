<?php
namespace Aquarium\Resources\Modules\Package;


interface IPackagesCollection
{
	/**
	 * @return array Array of all existing package definitions
	 */
	public function getNames();
}