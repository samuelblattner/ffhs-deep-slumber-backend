<?php


include_once __DIR__ . "/views/login_logout.php";
include_once __DIR__ . "/views/register.php";
include_once __DIR__ . "/views/manage_user.php";


$ROUTES = [
	["/^login\/$/", LoginView::class],
	["/^logout\/$/", LogoutView::class],
	["/^register\/$/", RegisterView::class],
	["/^user\/(?P<id>\d+)\/update\/$/", UpdateUserView::class]
];
