<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TaskRequest as StoreRequest;
use App\Http\Requests\TaskRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class TaskCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TaskCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Task');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/task');
        $this->crud->setEntityNameStrings('task', 'tasks');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumns([
            [
                'name' => 'course_id',
                'label' => 'Course',
                'type' => 'select',
                'model' => '\App\Models\Course',
                'attribute' => 'name',
                'entity' => 'course',
            ],
            [
                'name' => 'name',
                'label' => 'Name'
            ],
            [
                'name' => 'description',
                'label' => 'Description'
            ],
            [
                'name' => 'deadline',
                'label' => 'Deadline',
                'type' => 'datetime',
            ],
        ]);

        $this->crud->addFields([
            [
                'name' => 'course_id',
                'label' => 'Course',
                'type' => 'select',
                'model' => '\App\Models\Course',
                'attribute' => 'name2',
                'entity' => 'course',
            ],
            [
                'name' => 'name',
                'label' => 'Name'
            ],
            [
                'name' => 'description',
                'label' => 'Description'
            ],
            [
                'name' => 'deadline',
                'label' => 'Deadline',
                'type' => 'datetime',
            ],
        ]);

        // add asterisk for fields that are required in TaskRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
