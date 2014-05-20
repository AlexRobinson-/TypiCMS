<?php
namespace TypiCMS\Modules\Projects\Controllers;

use Str;
use View;

use TypiCMS\Modules\Projects\Repositories\ProjectInterface;

// Base controller
use TypiCMS\Controllers\BasePublicController;

class PublicController extends BasePublicController
{

    public function __construct(ProjectInterface $project)
    {
        parent::__construct($project);
        $this->title['parent'] = Str::title(trans_choice('projects::global.projects', 2));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($category = null)
    {
        $this->title['child'] = '';

        $relatedModels = array('translations', 'category', 'category.translations');

        if ($category) {
            $models = $this->repository->getAllBy('category_id', $category->id, $relatedModels, false);
        } else {
            $models = $this->repository->getAll($relatedModels, false);
        }

        $this->layout->content = View::make('projects.public.index')
            ->with('category', $category)
            ->with('models', $models);
    }

    /**
     * Show resource.
     *
     * @param  int      $id
     * @return Response
     */
    public function show($category = null, $slug = null)
    {
        $model = $this->repository->bySlug($slug);

        $this->title['parent'] = $model->title;

        $this->layout->content = View::make('projects.public.show')
            ->with('model', $model);
    }
}
