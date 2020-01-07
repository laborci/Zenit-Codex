<?php namespace Zenit\Bundle\Codex\Component\Action;

use Zenit\Bundle\Codex\Component\Codex\AdminDescriptor;
use Throwable;

class CodexAttachmentDelete extends Responder{

	protected function getRequiredPermissionType(): ?string{ return AdminDescriptor::PERMISSION; }

	protected function codexRespond(): ?array{
		try{
			$formHandler = $this->adminDescriptor->getFormHandler();
			$id = $this->getPathBag()->get('id');
			$file = $this->getJsonParamBag()->get('filename');
			$category = $this->getJsonParamBag()->get('category');
			$formHandler->deleteAttachment($id, $file, $category);
		}catch (Throwable $exception){
			$this->getResponse()->setStatusCode(400);
			return['message'=>$exception->getMessage()];
		}
		return [];
	}

}
