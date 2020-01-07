<?php namespace Zenit\Bundle\Codex\Component\Page;

use Zenit\Bundle\Codex\Module\Codex;
use Zenit\Bundle\SmartPageResponder\Component\Responder\SmartPageResponder;
use Zenit\Core\Module\Component\ModuleLoader;

/**
 * @title     Admin
 * @bodyclass login
 * @template "@codex/Login.twig"
 */
class Login extends SmartPageResponder{

	/** @var \Zenit\Bundle\Codex\Module\Codex */
	protected $module;

	public function __construct(ModuleLoader $moduleLoader) {
		parent::__construct();
		$this->module = $moduleLoader->get(Codex::class);
		$this->css = [$this->module->getEnv()['frontend-prefix'].'login.css'];
		$this->js = [$this->module->getEnv()['frontend-prefix'].'login.js'];
		$this->title = $this->module->getAdmin()['title'];
	}

	function prepare(){
		$this->getDataBag()->set('admin', $this->module->getAdmin());
	}

}