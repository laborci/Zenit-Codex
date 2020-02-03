<?php namespace Zenit\Bundle\Codex\Component\Codex;


use Application\AdminCodex\Menu;
use Zenit\Bundle\Codex\Component\Codex\AdminRegistry;
use Zenit\Bundle\Codex\Interfaces\CodexWhoAmIInterface;
use Zenit\Core\Code\Component\CodeFinder;
class CodexInitializer{

	public $title = 'Zenit Codex';
	public $icon = 'fak fa-infinite';
	public $loginPlaceholder = 'login';
	private $whoAmI;

	public function __construct(CodexWhoAmIInterface $whoAmI){
		$this->whoAmI = $whoAmI;
	}

	public function getMenu(){
		$menu = new CodexMenu();
		$this->menu($menu);
		return $menu->extract($this->whoAmI);
	}

	protected function autoMap($namespace, AdminRegistry $registry){
		$cw = new CodeFinder();
		$classes = $cw->Psr4ClassSeeker($namespace);
		foreach ($classes as $class){
			$registry->registerForm($class);
		}
	}
}