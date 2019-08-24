<?php declare(strict_types=1);


final class HomepageTemplateFactory
{

	/** @var LayoutView */
	private $layoutView;

	public function __construct(LayoutView $layoutView)
	{
		$this->layoutView = $layoutView;
	}

	public function createDefault(array $posts): HomepageDefaultView {
		return new HomepageDefaultView($this->layoutView, $posts);
	}

}
