<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Subject;

final class SubjectsController extends ResourceController
{
    protected function resource(): array
    {
        return [
            'model'      => Subject::class,
            'table'      => 'subjects',
            'route'      => 'subjects',
            'title'      => 'Subjects',
            'singular'   => 'Subject',
            'activeMenu' => 'subjects',
            'order'      => 'sort_order ASC, name ASC',
            'references' => [
                ['table' => 'curriculum_pages', 'column' => 'subject_id', 'label' => 'curriculum page(s)'],
                ['table' => 'courses', 'column' => 'subject_id', 'label' => 'course(s)'],
                ['table' => 'topics', 'column' => 'subject_id', 'label' => 'topic(s)'],
                ['table' => 'posts', 'column' => 'subject_id', 'label' => 'blog post(s)'],
                ['table' => 'bookings', 'column' => 'subject_id', 'label' => 'booking(s)'],
            ],
            'columns'    => ['name' => 'Name', 'code' => 'Code', 'sort_order' => 'Order', 'is_active' => 'Active'],
            'fields'     => [
                ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'rules' => 'required|max:120'],
                ['name' => 'code', 'label' => 'Code', 'type' => 'text', 'rules' => 'required|max:30', 'unique' => true, 'hint' => 'Unique key, e.g. math, physics'],
                ['name' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'default' => 0],
                ['name' => 'is_active', 'label' => 'Active', 'type' => 'checkbox', 'checkbox_label' => 'Visible on website', 'default' => 1],
            ],
        ];
    }
}
