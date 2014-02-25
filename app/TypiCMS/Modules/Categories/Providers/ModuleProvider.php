<?php namespace TypiCMS\Modules\Categories\Providers;

use View;
use Illuminate\Support\ServiceProvider;

// Model
use TypiCMS\Modules\Categories\Models\Category;

// Repo
use TypiCMS\Modules\Categories\Repositories\EloquentCategory;

// Cache
use TypiCMS\Modules\Categories\Repositories\CacheDecorator;
use TypiCMS\Services\Cache\LaravelCache;

// Form
use TypiCMS\Modules\Categories\Services\Form\CategoryForm;
use TypiCMS\Modules\Categories\Services\Form\CategoryFormLaravelValidator;

class ModuleProvider extends ServiceProvider {

	public function boot()
	{
		// Bring in the routes
		require __DIR__ . '/../routes.php';

		// Require breadcrumbs
		// require __DIR__ . '/../breadcrumbs.php';

		// Add view dir
		View::addLocation(__DIR__ . '/../Views');
	}

	public function register()
	{

		$app = $this->app;

		$app->bind('TypiCMS\Modules\Categories\Repositories\CategoryInterface', function($app)
		{
			require __DIR__ . '/../breadcrumbs.php';
			$repository = new EloquentCategory(new Category);
			$laravelCache = new LaravelCache($app['cache'], 'Categories', 10);
			return new CacheDecorator($repository, $laravelCache);
		});

		$app->bind('TypiCMS\Modules\Categories\Services\Form\CategoryForm', function($app)
		{
			return new CategoryForm(
				new CategoryFormLaravelValidator( $app['validator'] ),
				$app->make('TypiCMS\Modules\Categories\Repositories\CategoryInterface')
			);
		});

	}

}