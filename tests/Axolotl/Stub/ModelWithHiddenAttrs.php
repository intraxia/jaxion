<?php
namespace Intraxia\Jaxion\Test\Axolotl\Stub;

class ModelWithHiddenAttrs extends DefaultModel {
	protected $visible = array();

	protected $hidden = array( 'ID' );
}