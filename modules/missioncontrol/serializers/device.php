<?php

include_once __DIR__ . '/../../common/serializers/serializer.php';


class DeviceSerializer extends AbstractModelSerializer {

	protected $MODEL_FIELDS = [
		array(
			'fieldname' => 'hwid',
			'getter' => 'getHwid'
		)
	];

}