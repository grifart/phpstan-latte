<?php declare(strict_types=1);


class LayoutParameters implements ViewParameters
{
	/** @var FlashMessage[] */
	private $flashes;

	public function __construct(array $flashes)
	{
		$this->flashes = $flashes;
	}

	/**
	 * @return array{flashes:FlashMessage[]}
	 */
	public function getParameters(): array
	{
		return [
			'flashes' => $this->flashes
		];
	}




}
