<?php namespace Zenit\Bundle\Codex\Component\Codex;

use Zenit\Bundle\Codex\Component\Codex\FormHandler\FormHandler;
use Zenit\Bundle\Codex\Component\Codex\ListHandler\ListHandler;
use Zenit\Bundle\Codex\Interfaces\DataProviderInterface;
use Zenit\Core\ServiceManager\Component\Service;
use Zenit\Core\ServiceManager\Interfaces\SharedService;

abstract class AdminDescriptor implements SharedService{

	use Service;

	abstract protected function listHandler(ListHandler $codexList);
	abstract protected function formHandler(FormHandler $codexForm);
	abstract protected function createDataProvider(): DataProviderInterface;
	abstract protected function decorator(FormDecorator $decorator);

	public function getDataProvider(): DataProviderInterface{ return $this->createDataProvider(); }
	public function getDecorator(): FormDecorator{
		$decorator = new FormDecorator('fal fa-infinite', 'Zenit Codex', (new \ReflectionClass($this))->getShortName());
		$this->decorator($decorator);
		return $decorator;
	}
	public function getListHandler(): ListHandler{
		$listhandler = new ListHandler($this);
		$this->listHandler($listhandler);
		return $listhandler;
	}
	public function getFormHandler(): FormHandler{
		$formhandler = new FormHandler($this);
		$this->formHandler($formhandler);
		return $formhandler;
	}
}

