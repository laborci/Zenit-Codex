<?php namespace Zenit\Bundle\Codex\Component\Codex;

use Zenit\Core\ServiceManager\Component\Service;
use Zenit\Core\ServiceManager\Interfaces\SharedService;
class AdminRegistry implements SharedService{

	use Service;

	protected $admins = [];

	public function registerForm($form){
		$this->admins[(new \ReflectionClass($form))->getShortName()] = $form;
	}

	public function get($name):AdminDescriptor{
		/** @var \Zenit\Bundle\Codex\Component\Codex\AdminDescriptor $form */
		/** @var \Zenit\Bundle\Codex\Component\Codex\AdminDescriptor $codex */
		$form = $this->admins[$name];
		$codex = $form::Service();
		return $codex;
	}
}