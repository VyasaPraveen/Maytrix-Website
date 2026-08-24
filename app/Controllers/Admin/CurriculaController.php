<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Curriculum;

final class CurriculaController extends ResourceController
{
    protected function resource(): array
    {
        return [
            'model'      => Curriculum::class,
            'table'      => 'curricula',
            'route'      => 'curricula',
            'title'      => 'Curricula',
            'singular'   => 'Curriculum',
            'activeMenu' => 'curricula',
            'order'      => 'sort_order ASC, name ASC',
            'references' => [
                ['table' => 'curriculum_pages', 'column' => 'curriculum_id', 'label' => 'curriculum page(s)'],
                ['table' => 'courses', 'column' => 'curriculum_id', 'label' => 'course(s)'],
                ['table' => 'topics', 'column' => 'curriculum_id', 'label' => 'topic(s)'],
                ['table' => 'posts', 'column' => 'curriculum_id', 'label' => 'blog post(s)'],
                ['table' => 'bookings', 'column' => 'curriculum_id', 'label' => 'booking(s)'],
            ],
            'columns'    => ['name' => 'Name', 'code' => 'Code', 'short_name' => 'Short', 'sort_order' => 'Order', 'is_active' => 'Active'],
            'fields'     => [
                ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'rules' => 'required|max:120'],
                ['name' => 'code', 'label' => 'Code', 'type' => 'text', 'rules' => 'required|max:30', 'unique' => true, 'hint' => 'Unique key, e.g. ib_dp, igcse, alevel'],
                ['name' => 'short_name', 'label' => 'Short name', 'type' => 'text', 'rules' => 'max:60', 'placeholder' => 'e.g. IB DP'],
                ['name' => 'tagline', 'label' => 'Tagline', 'type' => 'text', 'rules' => 'max:190'],
                ['name' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'default' => 0],
                ['name' => 'is_active', 'label' => 'Active', 'type' => 'checkbox', 'checkbox_label' => 'Visible on website', 'default' => 1],
            ],
        ];
    }
}
