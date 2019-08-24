<?php declare(strict_types=1);


use Nette\Utils\Html;

class FlashMessage
{

	/**
	 * @var string
	 */
	public $type;

	/**
	 * @var string|Html
	 */
	public $message;

}
