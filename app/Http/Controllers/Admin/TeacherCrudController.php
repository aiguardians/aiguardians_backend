<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TeacherRequest as StoreRequest;
use App\Http\Requests\TeacherRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class TeacherCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TeacherCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Teacher');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/teacher');
        $this->crud->setEntityNameStrings('teacher', 'teachers');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addColumns([
            [
                'name' => 'user_id',
                'label' => 'User',
            ],
            [
                'name' => 'first_name',
                'lable' => 'First name',
            ],
            [
                'name' => 'last_name',
                'label' => 'Last name',
            ],
            [
                'name' => 'image',
                'label' => 'image',
                'type' => 'image',
            ],
            [
                'name' => 'appointment',
                'label' => 'appointment',
            ],
            [
                'name' => 'regalia',
                'label' => 'regalia',
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
                'name' => 'appointment',
                'label' => 'appointment',
            ],
            [
                'name' => 'regalia',
                'label' => 'regalia',
            ],
            [
                'name' => 'image',
                'label' => 'image',
                'type' => 'image',
                'upload' => true,
                'crop' => false,
            ],
        ]);

        // add asterisk for fields that are required in TeacherRequest
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
