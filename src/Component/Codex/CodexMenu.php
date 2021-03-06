<?php namespace Zenit\Bundle\Codex\Component\Codex;

use Zenit\Bundle\Codex\Interfaces\CodexWhoAmIInterface;
use Zenit\Core\ServiceManager\Component\ServiceContainer;

class CodexMenu{

	protected $items = [];
	/** @var bool */
	protected $submenu;

	public function __construct($submenu = false){
		$this->submenu = $submenu;
	}

	public function addItem($label, $icon, $event, $data = [], $role = null){
		$item = [
			'label'      => $label,
			'icon'       => $icon,
			'event'      => $event,
			'data'       => $data,
			'role' => $role,
		];
		$this->items[] = $item;
	}

	public function addCodexItem($class){
		/** @var \Zenit\Bundle\Codex\Component\Codex\AdminDescriptor $item */
		$item = ServiceContainer::get($class);
		$decorator = $item->getDecorator();
		$this->addItem($decorator->getTitleMenu(), $decorator->getIconMenu(), 'SHOW-FORM', ['name' => $decorator->getUrl()], $decorator->getRole());
	}

	public function addSubmenu($label, $icon){
		if ($this->submenu) throw new \Exception('Submenu cant contain submenus');
		$submenu = new static(true);
		$this->addItem($label, $icon, null, $submenu);
		return $submenu;
	}

	public function extract(CodexWhoAmIInterface $whoAmI){
		$extract = [];
		foreach ($this->items as $item){
			if (is_object($item['data']) && $item['data'] instanceof static){
				$extractedItem = [
					'label'   => $item['label'],
					'icon'    => $item['icon'],
					'submenu' => $item['data']->extract($whoAmI),
				];
				if (!empty($extractedItem['submenu'])) $extract[] = $extractedItem;
			}elseif ($whoAmI->checkRole($item['role'])){
				$extract[] = $item;
			}
		}
		return $extract;
	}
}