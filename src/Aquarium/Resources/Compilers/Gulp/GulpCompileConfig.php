<?php
namespace Aquarium\Resources\Compilers\Gulp;


use Objection\LiteSetup;
use Objection\LiteObject;
use Objection\Enum\AccessRestriction;


/**
 * @property bool 			$IsAddTimestamp
 * @property string			$TargetDirectory
 * @property IGulpAction[] 	$StyleActions
 * @property IGulpAction[]	$ScriptActions
 */
class GulpCompileConfig extends LiteObject
{
	/**
	 * @return array
	 */
	protected function _setup()
	{
		return [
			'IsAddTimestamp'	=> LiteSetup::createBool(false),
			'TargetDirectory'	=> LiteSetup::createString('./'),
			'StyleActions'		=> LiteSetup::createArray([], AccessRestriction::NO_SET),
			'ScriptActions'		=> LiteSetup::createArray([], AccessRestriction::NO_SET)
		];
	}
	
	
	/**
	 * @param IGulpAction $action
	 * @return static
	 */
	public function addStyleAction(IGulpAction $action)
	{
		$this->_p->StyleActions[] = $action;
		return $this;
	}
	
	/**
	 * @param IGulpAction $action
	 * @return static
	 */
	public function addScriptAction(IGulpAction $action)
	{
		$this->_p->ScriptActions[] = $action;
		return $this;
	}
}