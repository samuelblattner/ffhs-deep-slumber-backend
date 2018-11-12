<?php

include __DIR__.'/../../config/settings.php';
global $SETTINGS;
include $SETTINGS['main-routes-file'];


class Router {

	private function __sanitize($dirtyUrl) {
		return $dirtyUrl;
	}

	private function __findRoute($url, $use_routes=null): ?AbstractAPIView {
		global $ROUTES;
		foreach(($use_routes ? $use_routes : $ROUTES) as $route ) {
			$pattern = $route[0];
			$viewClass = $route[1];
			$vector = $route[2];

			$matches = array();
			$found = preg_match($pattern, $url, $matches);

			if ($found) {
				if ($vector !== null) {
					include $vector;
					return $this->__findRoute(preg_replace($pattern, '', $url), $ROUTES);
				} else if ($viewClass !== null) {
					return new $viewClass($matches);
				}
			}
		}
		return null;
	}

	public function getViewForUrl($url) {
		$view = $this->__findRoute($url);
		return $view;
	}
}
