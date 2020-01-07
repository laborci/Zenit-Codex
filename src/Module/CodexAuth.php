<?php namespace Zenit\Bundle\Codex\Module;

use Zenit\Bundle\Mission\Component\Web\WebMission;
use Zenit\Bundle\Mission\Module\Web\Routing\Router;
use Zenit\Core\Event\Component\EventManager;
use Zenit\Core\Module\Interfaces\ModuleInterface;
use Zenit\Bundle\Zuul\Component\Auth\Action\Login;
use Zenit\Bundle\Zuul\Component\Auth\Action\Logout;
use Zenit\Bundle\Zuul\Component\Auth\Middleware\AuthCheck;
use Zenit\Bundle\Zuul\Component\Auth\Middleware\PermissionCheck;

class CodexAuth implements ModuleInterface{

	protected $loginPage = false;
	protected $permission = false;

	public function load($env){
		if (array_key_exists('login-page', $env)) $this->loginPage = $env['login-page'];
		if (array_key_exists('permission', $env)) $this->permission = $env['permission'];
		EventManager::listen(WebMission::EVENT_ROUTING_BEFORE, [$this, 'route']);
	}

	public function route(Router $router){
		$router->post("/login", Login::class)();
		if ($this->loginPage){
			$router->pipe(AuthCheck::class, AuthCheck::config($this->loginPage));
			if ($this->permission){
				$router->pipe(PermissionCheck::class, PermissionCheck::config($this->loginPage, $this->permission, true));
			}
		}
		$router->post("/logout", Logout::class)();
	}

}


