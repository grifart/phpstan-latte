<?php declare(strict_types=1);


use App\Model\Post;
use Nette\Bridges\ApplicationLatte\ILatteFactory;

final class HomepageDefaultView implements View
{

	/**
	 * @var LayoutParameters
	 */
	private $layoutParameters;

	/**
	 * @var Post[]
	 */
	private $posts;

	/**
	 * @param Post[] $posts
	 */
	public function __construct(LayoutParameters $layoutParameters, array $posts)
	{
		$this->posts = $posts;
		$this->layoutParameters = $layoutParameters;
	}

	function render(ILatteFactory $factory): void
	{
		$factory->create()->render(
			__DIR__ . '/default.latte',
			$this->getTemplateParameters()
		);
	}

	public function configure(\Nette\Bridges\ApplicationLatte\Template $template): void
	{
		$template->setParameters($this->getTemplateParameters());
	}

	public function getTemplateParameters(): array {
		// todo: safe combine!
		return \array_merge($this->layoutParameters->getParameters(), [
			'posts' => $this->posts,
		]);
	}




}
