<?php namespace Zenit\Bundle\Codex\Module;

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
use Zenit\Bundle\Mission\Component\Web\WebMission;
use Zenit\Bundle\Mission\Constant\RoutingEvent;
use Zenit\Bundle\SmartPageResponder\Component\Twigger\Twigger;
use Zenit\Core\Event\Component\EventManager;
use Zenit\Core\Module\Interfaces\ModuleInterface;
use Zenit\Bundle\Mission\Module\Web\Routing\Router;
use Zenit\Core\ServiceManager\Component\ServiceContainer;
use Zenit\Bundle\Ghost\Thumbnail\Component\ThumbnailResponder;

class Codex implements ModuleInterface{

	/** @var \Zenit\Bundle\Codex\Component\Codex\AdminRegistry */
	private $adminRegistry;
	protected $moduleConfig;
	protected $config;

	public function __construct(AdminRegistry $adminRegistry, Config $config){
		$this->adminRegistry = $adminRegistry;
		$this->config = $config;
	}

	protected $menu;
	public function getMenu(){ return $this->menu; }

	protected $admin = [
		'title'             => 'Codex2',
		'icon'              => 'fal fa-infinite',
		'login-placeholder' => 'e-mail',
	];
	public function getAdmin(): array{ return $this->admin; }

	public function getEnv(){ return $this->moduleConfig; }

	public function load($moduleConfig){
		$this->moduleConfig = $moduleConfig;
		ServiceContainer::shared(CodexWhoAmIInterface::class)->service($moduleConfig['services']['WhoAmI']);

		if (array_key_exists('menu', $moduleConfig)) $this->menu = $moduleConfig['menu'];
		if (array_key_exists('admin', $moduleConfig)) $this->admin = $moduleConfig['admin'];
		EventManager::listen(RoutingEvent::FINISHED, [$this, 'route']);
		EventManager::listen(Twigger::EVENT_TWIG_ENVIRONMENT_CREATED, function (){
			Twigger::Service()->addPath(__DIR__ . '/Resource/twig/', 'codex');
		});
		if (array_key_exists('codex-forms', $moduleConfig)) foreach ($moduleConfig['codex-forms'] as $form) $this->register($form);
	}

	public function route(Router $router){
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


