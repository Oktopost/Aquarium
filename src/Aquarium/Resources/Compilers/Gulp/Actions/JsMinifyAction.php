<?php
namespace Aquarium\Resources\Compilers\Gulp\Actions;


use Aquarium\Resources\Compilers\Gulp\GulpActionType;


class JsMinifyAction extends AbstractGulpAction
{
	/**
	 * @return string Type of the file generated (should be css or js)
	 */
	protected function getFileType() { return 'js'; }
	
	/**
	 * @return string
	 */
	protected function getActionType() { return GulpActionType::JS_MINIFY; }
}