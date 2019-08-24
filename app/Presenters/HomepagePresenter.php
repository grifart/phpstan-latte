<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model\Post;
use Nette;
use Nette\Application\UI\ITemplate;
use Nette\Bridges\ApplicationLatte\Template;

/**
 * @property HomepageDefaultView|Template $template
 */
final class HomepagePresenter extends Nette\Application\UI\Presenter
{


	/**
	 * @var Nette\Bridges\ApplicationLatte\ILatteFactory @inject
	 */
	public $latteFactory;

	/** @var \HomepageTemplateFactory @inject */
	public $homepageTemplateFactory;

	public function actionDefault() {

		$this->template->posts = [];

		// .....

	}

	public function renderDefault()
	{

		$this->sendView(
			$this->homepageTemplateFactory->createDefault(
				$this->getPosts()
			)
		);
	}

	/** @return Post[] */
	private function getPosts(): array {
		return [
			new Post('Hello world', 'Lorem ipsum', new \DateTimeImmutable('2019-08-20 10:00')),
			new Post('Ahoj svete', 'Dolor sit amet', new \DateTimeImmutable('2019-08-20 12:00')),
		];
	}

	public function sendView(\RenderableView $view) {
		$this->sendResponse(new Nette\Application\Responses\CallbackResponse(function () use ($view) {
			$view->render($this->latteFactory);
		}));
	}

	private function getLayoutParameters(): \LayoutParameters
	{
		return new \LayoutParameters([]);
	}

}
