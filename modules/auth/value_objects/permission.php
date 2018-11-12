<?php

class Permission {
	public $key, $label;

	public function __toString() {
		return 'Permission "'.$this->label.'"';
	}
}
