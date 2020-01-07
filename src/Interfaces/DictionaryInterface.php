<?php namespace Zenit\Bundle\Codex\Interfaces;

interface DictionaryInterface{
	public function __invoke($key):string;
	public function getDictionary():array;
}