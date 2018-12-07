<?php


include_once __DIR__. '/views/statistics.php';
include_once __DIR__. '/views/ratings.php';
include_once __DIR__. '/views/alarms.php';


$ROUTES = [
	["/^statistics\/avg_world_sleep_hours\/$/", WorldAverageSleepHoursView::class],
	["/^ratings\/rate_last\/$/", RateLastWakeUpView::class],
	["/^ratings\/rating_required\/$/", RatingRequiredView::class],
	["/^alarms\/$/", AlarmListView::class],
	["/^alarms\/(?P<id>\d+)\/update\/$/", AlarmUpdateView::class]
];
