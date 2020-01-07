<?php namespace Zenit\Bundle\Codex\Interfaces;

use Zenit\Bundle\Zuul\Interfaces\WhoAmIInterface;

interface CodexWhoAmIInterface extends WhoAmIInterface{

	public function getName():string;
	public function getAvatar():string;

}