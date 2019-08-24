<?php declare(strict_types=1);


use App\Model\Post;
use Nette\Bridges\ApplicationLatte\ILatteFactory;

function HomepageDefaultView(ILatteFactory $factory): callable {

	/**
	 * @param FlashMessage[] $flashes
	 * @param Post[] $posts
	 */
	return static function(array $flashes, array $posts) use ($factory): void {
		$factory->create()->render(
			__DIR__ . '/default.latte',
			[
				'flashes' => $flashes,
				'posts' => $posts,
			]
		);
	};
}

