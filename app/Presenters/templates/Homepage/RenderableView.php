<?php declare(strict_types=1);


interface RenderableView extends View
{

	public function render(\Nette\Bridges\ApplicationLatte\ILatteFactory $factory): void;
}
