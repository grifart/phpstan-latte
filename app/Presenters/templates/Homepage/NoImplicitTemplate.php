<?php declare(strict_types=1);


use Nette\Application\UI\ITemplate;

final class NoImplicitTemplate
{

	/**
	 * @throws Nette\Application\BadRequestException if no template found
	 * @throws Nette\Application\AbortException
	 */
	public function sendTemplate(): void
	{
		throw new LogicException('This presenter does not provide any implicit template. Use View class instead.');
	}

	protected function createTemplate(): ITemplate
	{
		return new class implements ITemplate {

			/**
			 * Renders template to output.
			 */
			function render(): void
			{
				throw new LogicException('This presenter does not have any implicit template.');
			}

			/**
			 * Sets the path to the template file.
			 * @return static
			 */
			function setFile(string $file)
			{
				throw new LogicException('This presenter does not have any implicit template.');
			}

			/**
			 * Returns the path to the template file.
			 */
			function getFile(): ?string
			{
				throw new LogicException('This presenter does not have any implicit template.');
			}
		};
	}

}
