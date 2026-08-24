<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Topic;
use App\Models\Subject;
use App\Models\Curriculum;

final class TopicsController extends ResourceController
{
    protected function resource(): array
    {
        return [
            'model'      => Topic::class,
            'table'      => 'topics',
            'route'      => 'topics',
            'title'      => 'Topics',
            'singular'   => 'Topic',
            'activeMenu' => 'topics',
            'order'      => 'subject_id ASC, sort_order ASC',
            'columns'    => ['title' => 'Title', 'subject_id' => 'Subject', 'key_label' => 'Label', 'sort_order' => 'Order', 'is_active' => 'Active'],
            'fields'     => [
                ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'rules' => 'required|max:160'],
                ['name' => 'subject_id', 'label' => 'Subject', 'type' => 'select-int', 'options_key' => 'subjects', 'rules' => 'required'],
                ['name' => 'curriculum_id', 'label' => 'Curriculum (optional)', 'type' => 'select-int', 'options_key' => 'curricula', 'nullable' => true],
                ['name' => 'key_label', 'label' => 'Key label', 'type' => 'text', 'rules' => 'max:60', 'placeholder' => 'e.g. CORE, A LEVEL / IB'],
                ['name' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'default' => 0],
                ['name' => 'is_active', 'label' => 'Active', 'type' => 'checkbox', 'default' => 1],
            ],
        ];
    }

    protected function options(): array
    {
        return [
            'subjects'  => $this->optionMap(new Subject(), 'name', 'sort_order ASC'),
            'curricula' => $this->optionMap(new Curriculum(), 'name', 'sort_order ASC'),
        ];
    }

    protected function lookups(): array
    {
        return ['subject_id' => $this->optionMap(new Subject(), 'name')];
    }
}
