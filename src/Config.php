<?php namespace Zenit\Bundle\Codex;

use Zenit\Core\Env\Component\ConfigReader;
class Config extends ConfigReader{
	
	public $thumbnailUrl = 'bundle.codex.thumbnail-url';
	public $initializer = 'bundle.codex.initializer';
}