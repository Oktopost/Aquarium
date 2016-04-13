<?php
namespace Aquarium\Resources\Compilers\Gulp\Actions;


use Aquarium\Resources\Compilers\Gulp\GulpActionType;


class ScssAction extends AbstractGulpAction
{
	/**
	 * @return bool Will this action result in a single file.
	 */
	protected function isSingleFile() { return false; }
	
	/**
	 * @return string Type of the file generated (should be css or js)
	 */
	protected function getFileType() { return 'css'; }
	
	/**
	 * @return string
	 */
	protected function getActionType() { return GulpActionType::SCSS_PROCESS; }
	
	
	public function __construct()
	{
		$this->setFilter('*.scss');
	}
}