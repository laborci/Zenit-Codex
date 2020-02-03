<?php namespace Zenit\Bundle\Codex;

use Application\AdminCodex\Codex;
use Application\AdminCodex\CodexInit;
use Zenit\Bundle\Codex\Component\Action\CodexAttachmentCopy;
use Zenit\Bundle\Codex\Component\Action\CodexAttachmentDelete;
use Zenit\Bundle\Codex\Component\Action\CodexAttachmentGet;
use Zenit\Bundle\Codex\Component\Action\CodexAttachmentMove;
use Zenit\Bundle\Codex\Component\Action\CodexAttachmentUpload;
use Zenit\Bundle\Codex\Component\Action\CodexDeleteFormItem;
use Zenit\Bundle\Codex\Component\Action\CodexGetForm;
use Zenit\Bundle\Codex\Component\Action\CodexGetFormItem;
use Zenit\Bundle\Codex\Component\Action\CodexGetList;
use Zenit\Bundle\Codex\Component\Action\CodexInfo;
use Zenit\Bundle\Codex\Component\Action\CodexMenu;
use Zenit\Bundle\Codex\Component\Action\CodexSaveFormItem;
use Zenit\Bundle\Codex\Component\Codex\AdminRegistry;
use Zenit\Bundle\Codex\Component\Page\Index;
use Zenit\Bundle\Codex\Config;
use Zenit\Bundle\Codex\Interfaces\CodexWhoAmIInterface;
use Zenit\Bundle\Mission\Component\Web\Routing\Router;
use Zenit\Bundle\Mission\Component\Web\WebMission;
use Zenit\Bundle\Mission\Constant\RoutingEvent;
use Zenit\Bundle\SmartPageResponder\Component\Twigger\Twigger;
use Zenit\Bundle\Zuul\Component\Auth\Action\Login;
use Zenit\Bundle\Zuul\Component\Auth\Action\Logout;
use Zenit\Bundle\Zuul\Component\Auth\Middleware\AuthCheck;
use Zenit\Bundle\Zuul\Component\Auth\Middleware\RoleCheck;
use Zenit\Core\Event\Component\EventManager;
use Zenit\Core\Module\Interfaces\ModuleInterface;
use Zenit\Core\ServiceManager\Component\ServiceContainer;
use Zenit\Bundle\Ghost\Thumbnail\Component\ThumbnailResponder;

class Module implements ModuleInterface{

	/** @var \Zenit\Bundle\Codex\Component\Codex\AdminRegistry */
	private $adminRegistry;
	protected $moduleConfig;
	protected $config;
	protected $initializer;

	public function __construct(AdminRegistry $adminRegistry, Config $config){
		$this->adminRegistry = $adminRegistry;
		$this->config = $config;
	}

	protected $menu;
	public function getMenu(){ return $this->initializer->getMenu(); }

	public function getAdmin(): array{ return [
		'title'             => $this->initializer->title,
		'icon'              => $this->initializer->icon,
		'login-placeholder' => $this->initializer->loginPlaceholder
	]; }

	public function getEnv(){ return $this->moduleConfig; }

	public function load($moduleConfig){
		ServiceContainer::shared(CodexWhoAmIInterface::class)->service($moduleConfig['services']['WhoAmI']);

		$this->initializer = ServiceContainer::get($moduleConfig['initializer']);
		$this->initializer->importForms($this->adminRegistry);

		$this->moduleConfig = $moduleConfig;
		EventManager::listen(RoutingEvent::FINISHED, [$this, 'route']);
		EventManager::listen(Twigger::EVENT_TWIG_ENVIRONMENT_CREATED, function (){
			Twigger::Service()->addPath(__DIR__ . '/Resource/twig/', 'codex');
		});
		if (array_key_exists('codex-forms', $moduleConfig)) foreach ($moduleConfig['codex-forms'] as $form) $this->register($form);
	}

	public function route(Router $router){

		$router->post("/login", Login::class, ['role'=>$this->initializer->role])();
		$router->pipe(AuthCheck::class, AuthCheck::config(\Zenit\Bundle\Codex\Component\Page\Login::class));
		if ($this->initializer->role){
			$router->pipe(RoleCheck::class, RoleCheck::config(\Zenit\Bundle\Codex\Component\Page\Login::class, $this->initializer->role, true));
		}
		$router->post("/logout", Logout::class)();
		
		
		
		// PAGES
		$router->get($this->config->thumbnailUrl . '/*', ThumbnailResponder::class)();
		$router->get("/", Index::class)();

		$router->clearPipeline();
		
		// API AUTH
//		$router->pipe(AuthCheck::class, ["responder" => NotAuthorized::class]);
//		$router->pipe(PermissionCheck::class, ["responder" => Forbidden::class, "permission" => "admin"]);

		// API
		$router->get('/menu', CodexMenu::class)();
		$router->get('/{form}/codexinfo', CodexInfo::class)();
		$router->post('/{form}/get-list/{page}', CodexGetList::class)();
		$router->get('/{form}/get-form-item/{id}', CodexGetFormItem::class)();
		$router->get('/{form}/get-form', CodexGetForm::class)();
		$router->post('/{form}/save-item', CodexSaveFormItem::class)();
		$router->get('/{form}/delete-item/{id}', CodexDeleteFormItem::class)();
		$router->post('/{form}/attachment/upload/{id}', CodexAttachmentUpload::class)();
		$router->get('/{form}/attachment/get/{id}', CodexAttachmentGet::class)();
		$router->post('/{form}/attachment/move/{id}', CodexAttachmentMove::class)();
		$router->post('/{form}/attachment/copy/{id}', CodexAttachmentCopy::class)();
		$router->post('/{form}/attachment/delete/{id}', CodexAttachmentDelete::class)();

//		$router->get('/menu', Action\GetMenu::class)();
		$router->get('/', Index::class)();
	}

	public function register($form){ $this->adminRegistry->registerForm($form); }

}


