<?php namespace Zenit\Bundle\Codex\Component\Action;

use Zenit\Bundle\Codex\Module\Codex;
use Zenit\Bundle\Mission\Module\Web\Responder\JsonResponder;
use Zenit\Bundle\Codex\Component\Codex\AdminDescriptor;
use Zenit\Core\Module\Component\ModuleLoader;

class CodexMenu extends JsonResponder{

	protected function getRequiredPermissionType(): ?string{ return AdminDescriptor::PERMISSION; }

	protected function respond(): ?array{
		/** @var Codex $module */
		$module = ModuleLoader::Service()->get(Codex::class);
		return $module->getMenu();
	}

}

