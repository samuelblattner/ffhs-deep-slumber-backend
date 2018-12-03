<?php


include_once __DIR__.'/AbstractResponse.php';


abstract class GenericResponse extends AbstractResponse {

	protected $STATUS_CODE = 200;
	protected $message = '';

	public function __construct( ?int $status=200, ?string $message='' ) {
		parent::__construct( $this->STATUS_CODE, null );
		$this->message = $message;
		$this->header->content_type = 'text/plain';
	}

	public function renderResponse(): string {
		return $this->message;
	}
}

class NotFoundResponse extends  GenericResponse {
	protected $STATUS_CODE = 404;
}


class UnauthorizedResponse extends GenericResponse {
	protected $STATUS_CODE = 401;
}


class ServerErrorResponse extends GenericResponse {
	protected $STATUS_CODE = 500;
}

class BadRequestResponse extends GenericResponse {
	protected $STATUS_CODE = 400;
}


class MethodNotAllowedResponse extends GenericResponse {
	protected $STATUS_CODE = 405;
}


