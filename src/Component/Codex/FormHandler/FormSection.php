<?php namespace Zenit\Bundle\Codex\Component\Codex\FormHandler;

use Zenit\Bundle\Codex\Component\Codex\AdminDescriptor;
use JsonSerializable;
use Zenit\Bundle\Codex\Component\Codex\Field;

class FormSection implements JsonSerializable{
	/** @var \Zenit\Bundle\Codex\Component\Codex\FormHandler\FormInput[] */
	protected $inputs = [];
	protected $label;
	protected $adminDescriptor;

	public function __construct($label, AdminDescriptor $adminDescriptor){
		$this->label = $label;
		$this->adminDescriptor = $adminDescriptor;
	}

	public function input($type, Field $field, $label = null){
		if (is_null($label)){
			$label = $field->label;
		}
		$input = new FormInput($type, $label, $field->name);
		$this->inputs[] = $input;
		return $input;
	}

	public function jsonSerialize(){
		return [
			'label'  => $this->label,
			'inputs' => $this->inputs,
		];
	}

	/** @return \Zenit\Bundle\Codex\Component\Codex\FormHandler\FormInput[] */
	public function getInputs(): array{ return $this->inputs; }

}
