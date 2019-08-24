<?php declare(strict_types=1);


final class LayoutView implements View
{

	/** @var FlashMessage[] */
	private $flashes;

	/**
	 * LayoutView constructor.
	 * @param FlashMessage[] $flashes
	 */
	public function __construct(array $flashes)
	{
		$this->flashes = $flashes;
	}



	public function render(\Nette\Bridges\ApplicationLatte\ILatteFactory $factory): void
	{
		throw new LogicException('non sense');
	}

	public function getTemplateFile(): string {
		return __DIR__ . '/@layout.latte';
	}

	public function getTemplateParameters(): array
	{
		return [
			'flashes' => $this->flashes
		];
	}
}
