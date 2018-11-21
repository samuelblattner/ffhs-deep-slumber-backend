<?php

include_once __DIR__.'/AbstractResponse.php';


class RawFileResponse extends AbstractResponse {

	public function __construct( $status, $data, $content_type ) {
		parent::__construct( $status, $data );
		$this->header->content_type = $content_type;
	}

	public function renderResponse(): string {
		return $this->data;
	}
}