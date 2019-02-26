<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\StudentRequest as StoreRequest;
use App\Http\Requests\StudentRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class StudentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class StudentCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Student');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/student');
        $this->crud->setEntityNameStrings('student', 'students');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->addColumns([
            [
                'name' => 'first_name',
                'lable' => 'First name',
            ],
            [
                'name' => 'last_name',
                'label' => 'Last name',
            ],
            [
                'name' => 'user_id',
                'label' => 'User',
            ],
            [
                'name' => 'image',
                'label' => 'image',
                'type' => 'image',
            ],
        ]);

        $this->crud->addFields([
            [
                'name' => 'user_id',
                'label' => 'User',
                'type' => 'select',
                'model' => 'App\User',
                'entity' => 'user',
                'attribute' => 'name',
            ],
            [
                'name' => 'first_name',
                'label' => 'First name',
            ],
            [
                'name' => 'last_name',
                'label' => 'Last name',
            ],
            [
                'name' => 'image',
                'label' => 'image',
                'type' => 'image',
                'upload' => true,
                'crop' => false,
            ],
        ]);

        // add asterisk for fields that are required in StudentRequest
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
