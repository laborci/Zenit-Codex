<?php namespace Zenit\Bundle\Codex\Component\Action;

use Zenit\Bundle\Codex\Component\Codex\AdminRegistry;
use Zenit\Bundle\Mission\Component\Web\Responder\JsonResponder;
use Zenit\Bundle\Zuul\Component\AuthService;
use Symfony\Component\HttpFoundation\Response;
abstract class Responder extends JsonResponder{

	/** @var \Zenit\Bundle\Codex\Component\Codex\AdminDescriptor */
	protected $adminDescriptor;
	/** @var \Zenit\Bundle\Codex\Component\Codex\FormDecorator */
	protected $formDecorator;
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
		$this->formDecorator = $this->adminDescriptor->getDecorator();

		if (!$this->authService->checkRole($this->formDecorator->getRole())){
			$this->getResponse()->setStatusCode(Response::HTTP_FORBIDDEN);
			return false;
		}else return $this->codexRespond();
	}
	
	abstract protected function codexRespond(): ?array;
}