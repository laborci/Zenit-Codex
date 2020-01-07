<?php namespace Zenit\Bundle\Codex\Component\Action;

use Zenit\Bundle\Codex\Component\Codex\AdminRegistry;
use Zenit\Bundle\Mission\Module\Web\Responder\JsonResponder;
use Zenit\Bundle\Zuul\Component\AuthService;
use Symfony\Component\HttpFoundation\Response;
abstract class Responder extends JsonResponder{

	/** @var \Zenit\Bundle\Codex\Component\Codex\AdminDescriptor */
	protected $adminDescriptor;
	/** @var \Zenit\Bundle\Zuul\Component\AuthService */
	protected $authService;
	/** @var \Zenit\Bundle\Codex\Component\Codex\AdminRegistry */
	private $adminRegistry;

	public function __construct(AdminRegistry $adminRegistry, AuthService $authService){
		$this->authService = $authService;
		$this->adminRegistry = $adminRegistry;
	}

	protected function respond(){
		$this->adminDescriptor = $this->adminRegistry->get($this->getPathBag()->get('form'));
		if (!$this->authService->checkPermission($this->adminDescriptor->getPermission($this->getRequiredPermissionType()))){
			$this->getResponse()->setStatusCode(Response::HTTP_FORBIDDEN);
			return false;
		}else return $this->codexRespond();
	}

	abstract protected function getRequiredPermissionType(): ?string;

	abstract protected function codexRespond(): ?array;
}