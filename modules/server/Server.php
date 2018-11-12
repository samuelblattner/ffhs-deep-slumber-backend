<?php

include __DIR__.'/../router/Router.php';
include __DIR__.'/../common/value_objects/request.php';


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

		$r = new Router();
		$view = $r->getViewForUrl($_SERVER['REQUEST_URI']);

		if ($view === null) {

			if (file_exists(__DIR__.'/../../../frontend/index.html')) {
				require_once __DIR__.'/../../../frontend/index.html';
			} else {
				echo 'No frontend found!';
			}
		} else {
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
}
