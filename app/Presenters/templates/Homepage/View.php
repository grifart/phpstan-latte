<?php declare(strict_types=1);


interface View
{

	/**
	 * Provides template parameters for this view.
	 * @return array<string, mixed>
	 */
	public function getTemplateParameters(): array;

	/**
	 * Provides absolute path to Latte template file.
	 * @return string
	 */
	public function getTemplateFile(): string;

}
