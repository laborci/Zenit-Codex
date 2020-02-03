<?php namespace Zenit\Bundle\Codex\Component\Action;

use Zenit\Bundle\Codex\Component\Codex\AdminDescriptor;
use Throwable;

class CodexAttachmentMove extends Responder{
	
	protected function codexRespond(): ?array{
		$formHandler = $this->adminDescriptor->getFormHandler();

		try{
			$id = $this->getPathBag()->get('id');
			$file = $this->getJsonParamBag()->get('filename');
			$source = $this->getJsonParamBag()->get('source');
			$target = $this->getJsonParamBag()->get('target');
			return $formHandler->moveAttachment($id, $file, $source, $target);
		}catch (Throwable $exception){
			$this->getResponse()->setStatusCode(400);
			return['message'=>$exception->getMessage()];
		}
	}

}

