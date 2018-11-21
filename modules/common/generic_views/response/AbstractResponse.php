<?php

include_once __DIR__.'/ResponseHeader.php';


abstract class AbstractResponse {

	protected $header;
	protected $data;

	public function __construct( ?int $status, $data ) {
		$this->header = new ResponseHeader();
		$this->header->status = $status;
		$this->data = $data;
	}

	public function writeHttpResponseHeader() {
		http_response_code($this->header->status);
		header('Content-Type: '. $this->header->content_type);
	}

	public abstract function renderResponse(): string;
}