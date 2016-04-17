<?php
namespace Aquarium\Resources\Compilers\Gulp\Actions;


use Aquarium\Resources\Compilers\Gulp\GulpActionType;


class ConcatenateAction extends AbstractGulpAction
{
	/**
	 * @return string Type of the file generated (should be css or js)
	 */
	protected function getFileType() { return false; }
	
	/**
	 * @return string
	 */
	protected function getActionType() { return GulpActionType::CONCATENATE; }
}