<?php namespace Zenit\Bundle\Codex\Component\Action;

use Zenit\Bundle\Codex\Component\Codex\AdminDescriptor;
use Throwable;

class CodexDeleteFormItem extends Responder{

	protected function getRequiredPermissionType(): ?string{ return AdminDescriptor::PERMISSION; }

	protected function codexRespond(): ?array{

		$formHandler = $this->adminDescriptor->getFormHandler();
		$id = $this->getPathBag()->get('id');
		try{
			$formHandler->delete($id);
		}catch (Throwable $exception){
			$this->getResponse()->setStatusCode(400);
			return[
				'message'=>$exception->getMessage()
			];
		}
		return [];
	}

}

