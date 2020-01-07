<?php namespace Zenit\Bundle\Codex\Component\Codex;

use Zenit\Core\ServiceManager\Component\Service;
use Zenit\Core\ServiceManager\Interfaces\SharedService;
class AdminRegistry implements SharedService{

	use Service;

	protected $admins = [];

	public function registerForm($form, $config=null){
		$this->admins[(new \ReflectionClass($form))->getShortName()] = ['form'=>$form, 'config'=>$config];
	}

	public function get($name):AdminDescriptor{
		/** @var \Zenit\Bundle\Codex\Component\Codex\AdminDescriptor $form */
		/** @var \Zenit\Bundle\Codex\Component\Codex\AdminDescriptor $codex */
		$form = $this->admins[$name]['form'];
		$codex = $form::Service();
		$codex->setConfig($this->admins[$name]['config']);
		return $codex;
	}
}