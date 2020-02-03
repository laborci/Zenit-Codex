<?php namespace Zenit\Bundle\Codex\Component\Action;

use Zenit\Bundle\Codex\Module;
use Zenit\Bundle\Mission\Component\Web\Responder\JsonResponder;
use Zenit\Bundle\Codex\Component\Codex\AdminDescriptor;
use Zenit\Core\Module\Component\ModuleLoader;

class CodexMenu extends JsonResponder{
	
	protected function respond(): ?array{
		/** @var Module $module */
		$module = ModuleLoader::Service()->get(Module::class);
		return $module->getMenu();
	}

}

