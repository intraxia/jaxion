<?php
namespace Intraxia\Jaxion\Test\Axolotl\Stub;

use Intraxia\Jaxion\Axolotl\Model;

class DefaultModel extends Model {
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

	protected function get_post_type() {
		return 'custom';
	}

	protected function map_ID() {
		return 'ID';
	}

	protected function map_title() {
		return 'post_title';
	}

	protected function compute_url() {
		return 'example.com/' . $this->get_attribute( 'title' );
	}
}
