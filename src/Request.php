<?php
namespace App;
class Request
{
	private $method;
	private $methodArray;
	private $data;
	public function __construct()
	{
		$this->method = $_SERVER['REQUEST_METHOD'];
		$this->setMethodArray($this->method);
		$this->data = [];
	}

	private function setMethodArray($method)
    {
        $this->methodArray = ($method==='POST') ? $_POST : $_GET;
    }

    public function getMethod() : string
    {
        return $this->method;
    }

    public function isPost(): bool
    {
        return ($this->method === 'POST');
    }

    public function validate(array $args)
	{
        return Validator::run($args)->getValidatedData();
	}

	public function get(string $key)
    {
        return @$this->methodArray[$key] ?? '';
    }
}