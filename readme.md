Experiment: Strictly typed Latte files
======================================

This is just an experiment. Will evolve in future into something more production-ready.

## Stage 1: Add typing into Latte files

```latte
{type MainViewDTO $view}

{block #content}
    {$view->getProduct()}
```

phpstan:
```
 70     Class MainViewDTO not found.       
 80     Call to method getProduct() on an unknown class MainViewDTO.  
```

## Stage 2: Connect typing with Presenters and rest of application

Generated interface:

````php
/**
 * @property FlashMessage[] $flashes
 * @property MainViewDTO $view
 */
interface HomepageDefaultView
{
}
````

Which can then be used in presenter:
````php
/**
 * @property HomepageDefaultView|Template $template
 */
final class HomepagePresenter extends Nette\Application\UI\Presenter {
	
	public function renderDefault() {
		$this->template->view = ...; // Autocomplete works here, checked by PHPStan
	}
	
}
````


## Step 3: Latte and PHP become one thing

Problem with current Latte and template object is, that `Template` type represents all possible templates. What we need is to represent only one particular template file which exactly corresponds to our .latte file.

Another point of view can be, that template is function with following signature:

````php
function HomepageDefaultView(FlashMessage[] $flashes, MainViewDTO $view): string {
	// magic here
	// compiles Latte file, inits runtime, assignes variables, executes template and ends up with rendered data
}
````

This would be easy if we would be able to use functional approach (esp. type-safe closing of fn parameter). In PHP this is not possible.

So I ended up with following proposal. Each .latte file will have one `View` file which will declare variables used in global space of this template.

Templates which cannot be rendered will NOT have `render()` method. They can be only composed into another template which will have all blocks defined, therefore it is possible to render it.


@layout.latte
````latte
{type FlashMessage[] $flashes}
<html>
<body>
	<div n:foreach="$flashes as $flash" n:class="alert, 'alert-' . $flash->type">{$flash->message}</div>
	{include content}
</body>
</html>
````

LayoutView.php
````php
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
````


And `homepage.latte`:

````latte
{layout @layout.latte}
{type App\Model\Post[] $posts}

{define #content}
	<h1>block content</h1>
	{foreach $posts as $post}
		<h1>{$post->getTitle()}</h1>
	{/foreach}
{/define}
````

`HomepageView.php` (renderable view)
````php
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

	public function getTemplateParameters(): array {
		return \array_merge($this->layoutView->getTemplateParameters(), [
			'posts' => $this->posts,

		]);
	}


	public function getTemplateFile(): string
	{
		return __DIR__ . '/default.latte';
	}
}

````

View can then be used in presenter like this:

````php

final class HomepagePresenter extends Nette\Application\UI\Presenter
{
	public function renderDefault()
	{
		$this->sendView(
			new \HomepageDefaultViewWithLayoutView(
				new \LayoutView($this->getFlashes()),
				$this->getPosts()
			)
		);
	}
}
````



## Step 4: Latte becomes really (optionally) strict

Scopes in Latte aren't strict at all. It feels more like everything is available from all places. This is great for small projects, but this breaks reusability and isolation, when you want to build small library of reusable blocks.

````latte
{layout none}
{type App\Model\Post[] $posts}

{block $content}
	{* $posts type is inherited from this file global space *}
	{include #myBlock, $posts}
{/block}

{strictdefine #myBlock, Posts[] $list}
	{* accessing $posts will end up in error *}
	<h1>block content</h1>
	{foreach $list as $post}
		<h1>{$post->getTitle()}</h1>
	{/foreach}
{/strictdefine}
````

Strict define and strict block will allow to access only variables explicitly passed to them. This will lead to better modularity in Latte code and in more thought full usage of blocs.
