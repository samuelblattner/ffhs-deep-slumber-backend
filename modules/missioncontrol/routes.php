<?php

include_once __DIR__ . '/views/device.php';


$ROUTES = [
	["/^device\/$/", DeviceListAPIView::class],
];
