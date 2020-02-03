<?php namespace Zenit\Bundle\Codex\Component\Action;

use Zenit\Bundle\Codex\Component\Codex\AdminDescriptor;

class CodexInfo extends Responder{
	
	protected function codexRespond(): ?array{
		return [
			'header' => ['icon'=>$this->formDecorator->getIconHeader(), 'title'=>$this->formDecorator->getTitle()],
			'urlBase'=> $this->formDecorator->getUrl(),
			'list'   => $this->adminDescriptor->getListHandler(),
		];
	}
}
