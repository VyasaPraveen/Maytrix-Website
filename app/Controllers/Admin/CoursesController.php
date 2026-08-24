<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Course;
use App\Models\Subject;
use App\Models\Curriculum;

final class CoursesController extends ResourceController
{
    protected function resource(): array
    {
        return [
            'model'      => Course::class,
            'table'      => 'courses',
            'route'      => 'courses',
            'title'      => 'Courses',
            'singular'   => 'Course',
            'activeMenu' => 'courses',
            'order'      => 'sort_order ASC, id DESC',
            'references' => [
                ['table' => 'batches', 'column' => 'course_id', 'label' => 'batch(es)'],
            ],
            'columns'    => ['title' => 'Title', 'curriculum_id' => 'Curriculum', 'subject_id' => 'Subject', 'class_type' => 'Type', 'is_active' => 'Active'],
            'fields'     => [
                ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'rules' => 'required|max:190', 'slug_source' => true, 'makes_slug' => 'slug'],
                ['name' => 'slug', 'label' => 'Slug', 'type' => 'slug', 'from' => 'title', 'rules' => 'max:190', 'unique' => true, 'hint' => 'Leave blank to auto-generate from title'],
                ['name' => 'curriculum_id', 'label' => 'Curriculum', 'type' => 'select-int', 'options_key' => 'curricula', 'rules' => 'required'],
                ['name' => 'subject_id', 'label' => 'Subject', 'type' => 'select-int', 'options_key' => 'subjects', 'rules' => 'required'],
                ['name' => 'class_type', 'label' => 'Class type', 'type' => 'select', 'options' => ['small_group' => 'Small-Group', 'one_to_one' => '1-to-1'], 'rules' => 'required'],
                ['name' => 'level', 'label' => 'Level', 'type' => 'text', 'rules' => 'max:120', 'placeholder' => 'e.g. HL, AS, Extended'],
                ['name' => 'summary', 'label' => 'Summary', 'type' => 'textarea'],
                ['name' => 'description', 'label' => 'Full description', 'type' => 'richtext', 'hint' => 'HTML allowed'],
                ['name' => 'default_price', 'label' => 'Default price', 'type' => 'number', 'step' => '0.01'],
                ['name' => 'currency', 'label' => 'Currency', 'type' => 'select', 'options' => ['INR' => 'INR', 'USD' => 'USD', 'GBP' => 'GBP', 'EUR' => 'EUR', 'AED' => 'AED'], 'default' => 'INR'],
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
        return [
            'curriculum_id' => $this->optionMap(new Curriculum(), 'short_name'),
            'subject_id'    => $this->optionMap(new Subject(), 'name'),
        ];
    }
}
