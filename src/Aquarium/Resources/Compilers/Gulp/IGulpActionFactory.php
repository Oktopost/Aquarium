<?php
namespace Aquarium\Resources\Compilers\Gulp;


interface IGulpActionFactory
{
	/**
	 * @return IGulpAction
	 */
	public function scss();
	
	/**
	 * @param string|bool $filter
	 * @return IGulpAction
	 */
	public function concatenate($filter = false);
	
	/**
	 * @return IGulpAction
	 */
	public function jsmin();
	
	/**
	 * @return IGulpAction
	 */
	public function cssmin();
}