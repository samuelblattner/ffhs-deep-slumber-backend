<?php


include_once __DIR__.'/AbstractResponse.php';


abstract class GenericResponse extends AbstractResponse {

	protected $STATUS_CODE = 200;
	protected $message = '';

	public function __construct( ?int $status=200, ?string $message='' ) {
		parent::__construct( $this->STATUS_CODE, null );
		$this->message = $message != '' ? $message : $this->message;
		$this->header->content_type = 'text/plain';
	}

	public function renderResponse(): string {
		return $this->message;
	}
}

class ErrorResponse extends  GenericResponse {

	public function renderResponse(): string {
		return json_encode(array(
			'error' => $this->message
		));
	}
}

class NotFoundResponse extends  ErrorResponse {
	protected $STATUS_CODE = 404;
	protected $message = 'Ressource could not be found.';
}


class UnauthorizedResponse extends ErrorResponse {
	protected $STATUS_CODE = 401;
	protected $message = 'You are not allowed to perform this action at this time.';
}


class ServerErrorResponse extends ErrorResponse {
	protected $STATUS_CODE = 500;
	protected $message = 'The server encountered a problem. Please try again later.';
}

class BadRequestResponse extends ErrorResponse {
	protected $STATUS_CODE = 400;
	protected $message = 'Your request is not formed properly.';
}


class MethodNotAllowedResponse extends ErrorResponse {
	protected $STATUS_CODE = 405;
}


