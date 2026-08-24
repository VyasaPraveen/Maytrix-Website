<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Post;
use App\Models\Subject;
use App\Models\Curriculum;

final class PostsController extends ResourceController
{
    protected function resource(): array
    {
        return [
            'model'      => Post::class,
            'table'      => 'posts',
            'route'      => 'posts',
            'title'      => 'Blog / Resources',
            'singular'   => 'Article',
            'activeMenu' => 'posts',
            'order'      => 'id DESC',
            'columns'    => ['title' => 'Title', 'kicker' => 'Kicker', 'status' => 'Status', 'published_at' => 'Published'],
            'fields'     => [
                ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'rules' => 'required|max:190'],
                ['name' => 'slug', 'label' => 'URL slug', 'type' => 'slug', 'from' => 'title', 'rules' => 'max:190', 'unique' => true, 'hint' => 'Leave blank to auto-generate'],
                ['name' => 'kicker', 'label' => 'Kicker', 'type' => 'text', 'rules' => 'max:120', 'placeholder' => 'e.g. IB · Mathematics AA'],
                ['name' => 'excerpt', 'label' => 'Excerpt', 'type' => 'textarea', 'rules' => 'max:400'],
                ['name' => 'body_html', 'label' => 'Body (HTML)', 'type' => 'richtext'],
                ['name' => 'cover_image', 'label' => 'Cover image URL', 'type' => 'text', 'rules' => 'max:255'],
                ['name' => 'curriculum_id', 'label' => 'Curriculum (optional)', 'type' => 'select-int', 'options_key' => 'curricula', 'nullable' => true],
                ['name' => 'subject_id', 'label' => 'Subject (optional)', 'type' => 'select-int', 'options_key' => 'subjects', 'nullable' => true],
                ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['draft' => 'Draft', 'published' => 'Published'], 'default' => 'draft'],
                ['name' => 'published_at', 'label' => 'Publish date', 'type' => 'date'],
                ['name' => 'seo_title', 'label' => 'SEO title', 'type' => 'text', 'rules' => 'max:190'],
                ['name' => 'seo_description', 'label' => 'SEO description', 'type' => 'textarea', 'rules' => 'max:300'],
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
}
