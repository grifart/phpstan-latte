<?php declare(strict_types=1);


use Nette\Application\UI\Presenter;

trait NoImplicitLayout
{
	/**
	 * Finds layout template file name.
	 * @internal
	 */
	public function findLayoutTemplateFile(): ?string
	{
		return null;
	}


	/**
	 * Changes or disables layout.
	 * @param  string|bool  $layout
	 * @return static
	 */
	public function setLayout($layout)
	{
		throw new LogicException('Layout cannot be set dynamically when using static type checking.');
	}

}
