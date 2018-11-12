<?php

include_once __DIR__.'/AbstractResponse.php';


class JSONResponse extends AbstractResponse {

	public function __construct( $status, $data ) {
		parent::__construct( $status, $data );
		$this->header->content_type = 'application/json';
	}

	public function renderResponse(): string {
		return json_encode($this->data);
	}
}