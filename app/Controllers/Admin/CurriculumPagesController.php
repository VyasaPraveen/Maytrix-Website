<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\CurriculumPage;
use App\Models\Subject;
use App\Models\Curriculum;

final class CurriculumPagesController extends ResourceController
{
    protected function resource(): array
    {
        return [
            'model'      => CurriculumPage::class,
            'table'      => 'curriculum_pages',
            'route'      => 'curriculum-pages',
            'title'      => 'Curriculum Pages',
            'singular'   => 'Curriculum Page',
            'activeMenu' => 'curriculum-pages',
            'order'      => 'sort_order ASC, id ASC',
            'columns'    => ['title' => 'Title', 'slug' => 'Slug', 'curriculum_id' => 'Curriculum', 'subject_id' => 'Subject', 'is_published' => 'Published'],
            'fields'     => [
                ['name' => 'title', 'label' => 'Page title', 'type' => 'text', 'rules' => 'required|max:190'],
                ['name' => 'slug', 'label' => 'URL slug', 'type' => 'slug', 'from' => 'title', 'rules' => 'required|max:160', 'unique' => true, 'hint' => 'Appears in the URL: /curriculum/{slug}'],
                ['name' => 'curriculum_id', 'label' => 'Curriculum', 'type' => 'select-int', 'options_key' => 'curricula', 'rules' => 'required'],
                ['name' => 'subject_id', 'label' => 'Subject', 'type' => 'select-int', 'options_key' => 'subjects', 'rules' => 'required'],
                ['name' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text', 'rules' => 'max:160', 'placeholder' => 'e.g. IBDP · Mathematics'],
                ['name' => 'badges', 'label' => 'Badges (JSON array)', 'type' => 'json', 'hint' => 'e.g. ["AA HL","AA SL","AI HL"]'],
                ['name' => 'intro', 'label' => 'Intro paragraph', 'type' => 'textarea', 'rules' => 'required'],
                ['name' => 'topics', 'label' => 'Topics (JSON array of [title, description])', 'type' => 'json', 'placeholder' => '[["Functions & Algebra","Core techniques…"],["Calculus","Differentiation…"]]', 'hint' => 'Each item is a pair: [title, description]'],
                ['name' => 'body_html', 'label' => 'Extra body (HTML, optional)', 'type' => 'richtext'],
                ['name' => 'seo_title', 'label' => 'SEO title', 'type' => 'text', 'rules' => 'max:190'],
                ['name' => 'seo_description', 'label' => 'SEO description', 'type' => 'textarea', 'rules' => 'max:300'],
                ['name' => 'seo_keywords', 'label' => 'SEO keywords', 'type' => 'text', 'rules' => 'max:255'],
                ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'default' => 0],
                ['name' => 'is_published', 'label' => 'Published', 'type' => 'checkbox', 'checkbox_label' => 'Visible on website', 'default' => 1],
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
