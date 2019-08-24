<?php declare(strict_types=1);


use App\Model\Post;
use Nette\Bridges\ApplicationLatte\ILatteFactory;

final class HomepageDefaultView implements RenderableView
{

	/**
	 * @var Post[]
	 */
	private $posts;

	/**
	 * @var LayoutView
	 */
	private $layoutView;

	/**
	 * @param Post[] $posts
	 */
	public function __construct(LayoutView $layoutView, array $posts)
	{
		$this->posts = $posts;
		$this->layoutView = $layoutView;
	}

	function render(ILatteFactory $factory): void
	{
		$factory->create()->render(
			$this->getTemplateFile(),
			$this->getTemplateParameters()
		);
	}

	public function configure(\Nette\Bridges\ApplicationLatte\Template $template): void
	{
		$template->setParameters($this->getTemplateParameters());
	}

	public function getTemplateParameters(): array {
		// todo: safe combine!
		return \array_merge($this->layoutView->getTemplateParameters(), [
			'posts' => $this->posts,

		]);
	}


	public function getTemplateFile(): string
	{
		return __DIR__ . '/default.latte';
	}
}
