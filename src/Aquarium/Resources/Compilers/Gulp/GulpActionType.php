<?php
namespace Aquarium\Resources\Compilers\Gulp;


class GulpActionType
{
	use \Objection\TConstsClass;
	
	
	const SCSS_PROCESS		= 'scss-process';
	const CONCATENATE		= 'concatenate';
	const MINIFY			= 'minify';
	const ADD_TIME_MODIFIED	= 'add-tm';
}