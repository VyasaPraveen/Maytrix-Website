<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Student;

final class StudentsController extends ResourceController
{
    protected function resource(): array
    {
        return [
            'model'      => Student::class,
            'table'      => 'students',
            'route'      => 'students',
            'title'      => 'Students',
            'singular'   => 'Student',
            'activeMenu' => 'students',
            'order'      => 'id DESC',
            'columns'    => ['name' => 'Name', 'email' => 'Email', 'country' => 'Country', 'timezone' => 'Time zone'],
            'fields'     => [
                ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'rules' => 'required|max:160'],
                ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'rules' => 'required|email|max:190'],
                ['name' => 'phone', 'label' => 'Phone', 'type' => 'text', 'rules' => 'max:40'],
                ['name' => 'country', 'label' => 'Country', 'type' => 'country', 'rules' => 'max:80'],
                ['name' => 'timezone', 'label' => 'Time zone', 'type' => 'text', 'rules' => 'max:60', 'hint' => 'Auto-filled from the selected country — you can still edit it.'],
                ['name' => 'notes', 'label' => 'Notes', 'type' => 'textarea'],
            ],
        ];
    }
}
