<?php
namespace TypiCMS\Controllers;

use Config;
use Controller;
use Input;
use Lang;
use Paginator;
use Patchwork\Utf8;
use Redirect;
use Request;
use Response;
use Str;
use TypiCMS;
use View;

abstract class BaseAdminController extends Controller
{

    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'admin/master';

    protected $repository;
    protected $module;
    protected $route;
    protected $form;

    // The cool kids’ way of handling page titles.
    // https://gisglobal.github.com/jonathanmarvens/6017139
    public $applicationName;
    public $title  = array(
        'parent'   => '',
        'child'    => '',
        'h1'       => '',
    );

    public function __construct($repository = null, $form = null)
    {
        $this->repository = $repository;

        $this->module = $this->repository->getModel()->getTable();

        $this->route = $this->repository->getModel()->route;

        $this->form = $form;

        $this->applicationName = Config::get('typicms.' . Lang::getLocale() . '.websiteTitle');

        $instance = $this;
        View::composer($this->layout, function (\Illuminate\View\View $view) use ($instance) {
            $view->with('title', $instance->getTitle());
        });

        View::share('locales', Config::get('app.locales'));
        View::share('locale', Config::get('app.locale'));
        View::share('module', $this->module);
        View::share('route', $this->route);
    }

    public function getTitle()
    {
        $title = Utf8::ucfirst($this->title['parent']);
        if ($this->title['child']) {
            $title .= ' – ' . Utf8::ucfirst($this->title['child']);
        }
        $title .= ' – ' . $this->applicationName;

        return $title;
    }

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if (! is_null($this->layout)) {
            $layout = Request::ajax() ? 'admin/ajax' : $this->layout;
            $this->layout = View::make($layout);
        }
    }

    /**
     * List models
     * GET /admin/model
     */
    public function index()
    {
        $page = Input::get('page');

        $itemsPerPage = Config::get($this->module . '::admin.itemsPerPage');

        $data = $this->repository->byPage($page, $itemsPerPage, ['translations'], true);

        $models = Paginator::make($data->items, $data->totalItems, $itemsPerPage);

        $this->layout->content = View::make('admin.index')->withModels($models);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $model = $this->repository->getModel();
        $this->layout->content = View::make('admin.create')
            ->withModel($model);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit($model)
    {
        $this->layout->content = View::make('admin.edit')
            ->withModel($model);
    }

    /**
     * Show resource.
     *
     * @return Response
     */
    public function show($model)
    {
        return Redirect::route('admin.' . $this->module . '.edit', $model->id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {

        if ($model = $this->form->save(Input::all())) {
            return Input::get('exit') ?
                Redirect::route('admin.' . $this->module . '.index') :
                Redirect::route('admin.' . $this->module . '.edit', $model->id) ;
        }

        return Redirect::route('admin.' . $this->module . '.create')
            ->withInput()
            ->withErrors($this->form->errors());

    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update($model)
    {

        if (Request::ajax()) {
            return Response::json($this->repository->update(Input::all()));
        }

        if ($this->form->update(Input::all())) {
            return Input::get('exit') ?
                Redirect::route('admin.' . $this->module . '.index') :
                Redirect::route('admin.' . $this->module . '.edit', $model->id) ;
        }

        return Redirect::route('admin.' . $this->module . '.edit', $model->id)
            ->withInput()
            ->withErrors($this->form->errors());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy($model)
    {
        if ($this->repository->delete($model)) {
            if (! Request::ajax()) {
                return Redirect::back();
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function sort()
    {
        $this->repository->sort(Input::all());
    }
}
