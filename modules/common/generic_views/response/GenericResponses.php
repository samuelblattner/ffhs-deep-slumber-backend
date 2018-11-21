<?php


include_once __DIR__.'/AbstractResponse.php';


abstract class GenericResponse extends AbstractResponse {

	protected $STATUS_CODE = 200;

	public function __construct( ?int $status=200, ?array $data=null ) {
		parent::__construct( $this->STATUS_CODE, $data );
		$this->header->content_type = 'text/plain';
	}

	public function renderResponse(): string {
		return '';
	}
}

class NotFoundResponse extends  GenericResponse {
	protected $STATUS_CODE = 404;
}


class UnauthorizedResponse extends GenericResponse {
	protected $STATUS_CODE = 401;
}


class MethodNotAllowedResponse extends GenericResponse {
	protected $STATUS_CODE = 405;
}


