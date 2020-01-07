<?php namespace Zenit\Bundle\Codex\Interfaces;

interface ItemConverterInterface{
	public function convertItem($item):array;
}