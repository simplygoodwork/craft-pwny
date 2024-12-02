<?php
/**
 * @copyright Copyright (c) 2024 Good Work
 */

namespace simplygoodwork\pwny\models;

class Settings extends \craft\base\Model
{
    public bool $enabled = true;
	public bool $restrictToCpUsers = false;
    public string $message = "This password has previously appeared in a data breach and should never be used. If you've ever used it elsewhere before, change it! [More information](https://haveibeenpwned.com/Passwords).";

	public function defineRules(): array
	{
		return [
			[['enabled', 'restrictToCpUsers', 'message'], 'required'],
		];
	}
}
