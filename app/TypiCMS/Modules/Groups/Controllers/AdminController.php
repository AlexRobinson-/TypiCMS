<?php
namespace TypiCMS\Modules\Groups\Controllers;

use View;
use Input;
use Request;
use Redirect;
use Notification;

use TypiCMS\Modules\Groups\Repositories\GroupInterface;
use TypiCMS\Modules\Groups\Services\Form\GroupForm;

// Base controller
use TypiCMS\Controllers\BaseAdminController;

class AdminController extends BaseAdminController
{

    /**
     * __construct
     *
     * @param Groupnterface $group
     * @param GroupForm     $groupform
     */
    public function __construct(GroupInterface $group, GroupForm $groupForm)
    {
        parent::__construct($group, $groupForm);
        $this->title['parent'] = trans_choice('groups::global.groups', 2);

        // Establish Filters
        $this->beforeFilter('inGroup:Admins');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $models = $this->repository->all();

        $this->layout->content = View::make('admin.groups.index')->withModels($models);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->title['child'] = trans('groups::global.New');

        $this->layout->content = View::make('admin.groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        // Form Processing
        $result = $this->form->save(Input::all());

        if ($result['success']) {
            // Success!
            Notification::success($result['message']);

            return Redirect::route('admin.groups.index');

        } else {
            Notification::error($result['message']);

            return Redirect::route('admin.groups.create')
                ->withInput()
                ->withErrors($this->form->errors());
        }
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function show($id)
    {
        //Show a group and its permissions.
        $group = $this->repository->byId($id);

        $this->layout->content = View::make('admin.groups.show')->with('group', $group);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->title['child'] = trans('groups::global.Edit');

        $group = $this->repository->byId($id);
        $this->layout->content = View::make('admin.groups.edit')
            ->withPermissions($group->getPermissions())
            ->with('group', $group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update($id)
    {
        // Form Processing
        $result = $this->form->update(Input::all());

        if ($result['success']) {
            // Success!
            Notification::success($result['message']);

            return Redirect::route('admin.groups.index');

        } else {
            Notification::error($result['message']);

            return Redirect::route('admin.groups.edit', $id)
                ->withInput()
                ->withErrors($this->form->errors());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int      $id
     * @return Response
     */
    public function destroy($id)
    {
        if ($this->repository->destroy($id)) {
            if (! Request::ajax()) {
                return Redirect::back();
            }
        }
    }
}
