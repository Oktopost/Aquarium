<?php
namespace Aquarium\Resources\Compilers\Gulp\Actions;


use Aquarium\Resources\Compilers\Gulp\IGulpAction;
use Aquarium\Resources\Compilers\Gulp\IGulpActionFactory;


class ActionFactory implements IGulpActionFactory
{
	/**
	 * @return IGulpAction
	 */
	public function scss()
	{
		return new ScssAction();
	}
	
	/**
	 * @param string|bool $filter
	 * @return IGulpAction
	 */
	public function concatenate($filter = false)
	{
		$action = new ConcatenateAction();
		
		if ($filter) $action->setFilter($filter);
		
		return $action;
	}
	
	/**
	 * @return IGulpAction
	 */
	public function jsmin()
	{
		return new JsMinifyAction();
	}
	
	/**
	 * @return IGulpAction
	 */
	public function cssmin()
	{
		return new CssMinifyAction();
	}
}