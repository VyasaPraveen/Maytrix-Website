<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Page;

final class PagesController extends ResourceController
{
    protected function resource(): array
    {
        return [
            'model'      => Page::class,
            'table'      => 'pages',
            'route'      => 'pages',
            'title'      => 'Pages',
            'singular'   => 'Page',
            'activeMenu' => 'pages',
            'order'      => 'id ASC',
            'columns'    => ['title' => 'Title', 'slug' => 'Slug', 'is_published' => 'Published'],
            'fields'     => [
                ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'rules' => 'required|max:190'],
                ['name' => 'slug', 'label' => 'URL slug', 'type' => 'slug', 'from' => 'title', 'rules' => 'required|max:160', 'unique' => true, 'hint' => 'e.g. about'],
                ['name' => 'body_html', 'label' => 'Body (HTML)', 'type' => 'richtext'],
                ['name' => 'seo_title', 'label' => 'SEO title', 'type' => 'text', 'rules' => 'max:190'],
                ['name' => 'seo_description', 'label' => 'SEO description', 'type' => 'textarea', 'rules' => 'max:300'],
                ['name' => 'is_published', 'label' => 'Published', 'type' => 'checkbox', 'default' => 1],
            ],
        ];
    }
}
