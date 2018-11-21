<?php

include __DIR__.'/../router/Router.php';
include __DIR__.'/../common/value_objects/request.php';
global $SETTINGS;
include $SETTINGS['main-routes-file'];

class Server {

	/**
	 * Collect request data and store it in the
	 * value object Request accordingly.
	 * @return Request
	 */
	private function __createRequest(): Request {
		$r = new Request();
		$r->getData = $_GET;
		$r->postData = $_POST;
		$json = file_get_contents('php://input');
		$r->postData = json_decode($json, true);
		return $r;
	}

	/**
	 * Main request entry point.
	 * @return Response
	 */
	public function handleRequest() {

		global $SETTINGS;


		$r = new Router();
		$view = $r->getViewForUrl($_SERVER['REQUEST_URI']);

		if ($view === null) {
			$view = new NotFoundView();
			$_SERVER['REQUEST_METHOD'] = 'GET';
		}
			$response = null;


			switch($_SERVER['REQUEST_METHOD']) {
				case 'POST': {
					$response = $view->post($this->__createRequest());
					break;
				}
				case 'PATCH': {
					$response = $view->patch($this->__createRequest());
					break;
				}
				default: {
					$response = $view->get($this->__createRequest());
				}
			}

			$response->writeHttpResponseHeader();
			echo $response->renderResponse();

	}
}
