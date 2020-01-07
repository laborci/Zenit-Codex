<?php namespace Zenit\Bundle\Codex\Component\Page;

use Zenit\Bundle\Codex\Interfaces\CodexWhoAmIInterface;
use Zenit\Bundle\Codex\Module\Codex;
use Zenit\Bundle\SmartPageResponder\Component\Responder\SmartPageResponder;
use Zenit\Core\Module\Component\ModuleLoader;

/**
 * @title Admin
 * @template "@codex/Index.twig"
 */
class Index extends SmartPageResponder {

	private $whoAmI;

	/** @var \Zenit\Bundle\Codex\Module\Codex  */
	protected $module;

	public function __construct(CodexWhoAmIInterface $whoAmI, ModuleLoader $moduleLoader) {
		parent::__construct();
		$this->module = $moduleLoader->get(Codex::class);
		$this->css = [$this->module->getEnv()['frontend-prefix'].'app.css'];
		$this->js = [$this->module->getEnv()['frontend-prefix'].'app.js'];
		$this->whoAmI = $whoAmI;
		$this->title = $this->module->getAdmin()['title'];
	}

	function prepare() {
		$this->getDataBag()->set('admin', $this->module->getAdmin());
		$this->getDataBag()->set('user', $this->whoAmI->getName());
		$this->getDataBag()->set('avatar', $this->whoAmI->getAvatar());
	}

}