<?php namespace Zenit\Bundle\Codex\Component\Action;

use Zenit\Bundle\Codex\Component\Codex\AdminDescriptor;
use Throwable;

class CodexAttachmentUpload extends Responder{

	protected function getRequiredPermissionType(): ?string{ return AdminDescriptor::PERMISSION; }

	protected function codexRespond(): ?array{
		$formHandler = $this->adminDescriptor->getFormHandler();
		try{
			$id = $this->getPathBag()->get('id');
			$category = $this->getRequestBag()->get('category');
			/** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
			$file = $this->getFileBag()->get('file');
			$formHandler->uploadAttachment($id, $category, $file);
		}catch (Throwable $exception){
			$this->getResponse()->setStatusCode(400);
			return['message'=>$exception->getMessage()];
		}
		return [];
	}

}

