<?php
namespace Intraxia\Jaxion\Test\Axolotl\Stub;

class ModelWithNoHiddenVisibleAttrs extends DefaultModel {
	protected $hidden = array();

	protected $visible = array();
}