<?php namespace Zenit\Bundle\Codex\Component\Action;

use Zenit\Bundle\Codex\Component\Codex\AdminDescriptor;

class CodexGetForm extends Responder{
	
	protected function codexRespond(): ?array{

		$formHandler = $this->adminDescriptor->getFormHandler();

		return [
			'descriptor'=>$formHandler
		];
	}

}

