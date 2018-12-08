<?php

include __DIR__.'/../../config/settings.php';
include __DIR__.'/../common/generic_views/views.php';

global $SETTINGS;
include $SETTINGS['main-routes-file'];


class Router {

	private static $CONTENT_TYPE_MAPPING = [
		'.js' => 'application/js',
		'.css' => 'text/css',
		'.woff' => 'font/woff',
		'.woff2' => 'font/woff2'
	];

	private function __sanitize($dirtyUrl) {
		return $dirtyUrl;
	}

	private function __findRoute($url, $use_routes=null, $failSilently=true): ?AbstractAPIView {
		global $ROUTES;
		global $SETTINGS;

		if ($SETTINGS['debug'] && preg_match($SETTINGS['assets-pattern'], $url)) {
			if (strpos($url, '..')) {
				return null;
			}

			$remainder = preg_replace($SETTINGS['assets-pattern'], '', $url);
			$ext = substr($remainder, strrpos($remainder, '.'));
			$syspath = $SETTINGS['assets-path'].$remainder;

			if (!Router::$CONTENT_TYPE_MAPPING[$ext] || !file_exists($syspath)) {
				return new NotFoundView();
			}

			$contentType = Router::$CONTENT_TYPE_MAPPING[$ext];
			return new RawFileView($syspath, $contentType);

		}

		foreach(($use_routes ? $use_routes : $ROUTES) as $route ) {
			$pattern = $route[0];
			$viewClass = $route[1];
			$vector = sizeof($route) > 2 ? $route[2] : null;

			$matches = array();
			$found = preg_match($pattern, $url, $matches);

			if ($found) {
				if ($vector !== null) {
					include $vector;
					return $this->__findRoute(preg_replace($pattern, '', $url), $ROUTES, false);
				} else if ($viewClass !== null) {
					return new $viewClass($matches);
				}
			}
		}
		return $failSilently ? new FrontendView() : null;
	}

	public function getViewForUrl($url) {
		$view = $this->__findRoute($url);
		return $view;
	}
}
