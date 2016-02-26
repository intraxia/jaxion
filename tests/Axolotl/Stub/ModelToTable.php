<?php
namespace Intraxia\Jaxion\Test\Axolotl\Stub;

use Intraxia\Jaxion\Axolotl\Model;

class ModelToTable extends Model {
	protected $fillable = array(
		'title',
		'text',
	);

	protected $guarded = array(
		'ID',
	);

	protected $visible = array(
		'title',
		'text',
		'url',
	);

	protected $post = false;
}