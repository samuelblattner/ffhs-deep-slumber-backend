<?php


include_once __DIR__ . "/views/login_logout.php";
include_once __DIR__ . "/views/register.php";
include_once __DIR__ . "/views/manage_user.php";
include_once __DIR__ . "/views/utils.php";


$ROUTES = [
	["/^login\/$/", LoginView::class],
	["/^logout\/$/", LogoutView::class],
	["/^register\/$/", RegisterView::class],
	["/^checkusername\/?/", CheckUserNameView::class],
	["/^user\/(?P<id>\d+)\/update\/$/", UpdateUserView::class]
];
