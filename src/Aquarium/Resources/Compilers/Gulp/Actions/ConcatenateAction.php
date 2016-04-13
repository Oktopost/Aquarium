<?php
namespace Aquarium\Resources\Compilers\Gulp\Actions;


use Aquarium\Resources\Compilers\Gulp\GulpActionType;


class ConcatenateAction extends AbstractGulpAction
{
	/**
	 * @return bool Will this action result in a single file.
	 */
	protected function isSingleFile() { return true; }
	
	/**
	 * @return string Type of the file generated (should be css or js)
	 */
	protected function getFileType() { return false; }
	
	/**
	 * @return string
	 */
	protected function getActionType() { return GulpActionType::CONCATENATE; }
}