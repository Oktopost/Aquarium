<?php
namespace Aquarium\Resources\Package;


interface IPackagesCollection
{
	/**
	 * @return array Array of all existing package definitions
	 */
	public function getNames();
}