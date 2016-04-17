<?php
namespace Aquarium\Resources\Compilers\Gulp\Actions;


use Aquarium\Resources\Compilers\Gulp\GulpActionType;


class CssMinifyAction extends AbstractGulpAction
{
	/**
	 * @return string Type of the file generated (should be css or js)
	 */
	protected function getFileType() { return 'css'; }
	
	/**
	 * @return string
	 */
	protected function getActionType() { return GulpActionType::CSS_MINIFY; }
}