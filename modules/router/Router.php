<?php

include __DIR__.'/../../config/settings.php';
include __DIR__.'/../common/generic_views/views.php';

global $SETTINGS;
include $SETTINGS['main-routes-file'];


/**
 * Class Router
 *
 * Main class to resolve URL-patterns to views.
 */
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

	/**
	 * Resolve url into its components until a pattern matches or url is consumed.
	 *
	 * @param $url
	 * @param null $use_routes
	 * @param bool $failSilently
	 *
	 * @return AbstractAPIView|null
	 */
	private function __findRoute($url, $use_routes=null, $failSilently=true): ?AbstractAPIView {

		global $ROUTES;
		global $SETTINGS;

		/**
		 * If server is in debug mode, serve asset files here.
		 * On live systems, assets will be served by a native server such as NginX.
		 */
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

		/**
		 * Recursive call for route-resolution.
		 */
		foreach(($use_routes ? $use_routes : $ROUTES) as $route ) {

			$pattern = $route[0];
			$viewClass = $route[1];
			$vector = sizeof($route) > 2 ? $route[2] : null;

			$matches = array();
			$found = preg_match($pattern, $url, $matches);

			if ($found) {

				/**
				 * Route configurations can contain 'vectors', i.e. pointers to further route configurations.
				 * If this is the case for a given pattern, include the subsequent routes by
				 * calling this method recursively.
				 * Otherwise, return the matched view.
				 */
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

	/**
	 * Retrieve view for a given url. Returns null if no pattern matches.
	 *
	 * @param $url
	 *
	 * @return AbstractAPIView|null
	 */
	public function getViewForUrl($url) {
		$view = $this->__findRoute($url);
		return $view;
	}
}
