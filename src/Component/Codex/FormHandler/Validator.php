<?php namespace Zenit\Bundle\Codex\Component\Codex\FormHandler;

abstract class Validator implements \JsonSerializable{

	protected $options = [];

	public function jsonSerialize(){
		return [
			'type'    => (new \ReflectionClass($this))->getShortName(),
			'options' => $this->options,
		];
	}
	
	abstract public function validate($value);
}