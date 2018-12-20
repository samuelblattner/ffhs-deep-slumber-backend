<?php


include_once __DIR__. '/views/statistics.php';
include_once __DIR__. '/views/ratings.php';
include_once __DIR__. '/views/alarms.php';
include_once __DIR__. '/views/simulation.php';


$ROUTES = [
	["/^statistics\/avg_world_sleep_hours\/$/", WorldAverageSleepHoursView::class],
	["/^statistics\/user_stats\/$/", UserSleepStatisticsView::class],
	["/^statistics\/user_last_sleep_profile\/$/", UserLastSleepProfileView::class],
	["/^ratings\/rate_last\/$/", RateLastWakeUpView::class],
	["/^ratings\/rating_required\/$/", RatingRequiredView::class],
	["/^alarms\/$/", AlarmListView::class],
	["/^alarms\/(?P<id>\d+)\/update\/$/", AlarmUpdateView::class],
	["/^simulation\/generate_sleep_cycle\/$/", GenerateSleepCycleView::class]
];
