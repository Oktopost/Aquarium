<?php
namespace Aquarium\Resources\Compilers\Gulp;


class GulpActionType
{
	use \Objection\TConstsClass;
	
	
	const SCSS_PROCESS		= 'scss-process';
	const CONCATENATE		= 'concatenate';
	const CSS_MINIFY		= 'css-minify';
	const JS_MINIFY			= 'js-minify';
}