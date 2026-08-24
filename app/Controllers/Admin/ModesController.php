<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Mode;

final class ModesController extends ResourceController
{
    protected function resource(): array
    {
        return [
            'model'      => Mode::class,
            'table'      => 'modes',
            'route'      => 'modes',
            'title'      => 'Delivery Modes',
            'singular'   => 'Mode',
            'activeMenu' => 'modes',
            'order'      => 'sort_order ASC',
            'references' => [
                ['table' => 'batches', 'column' => 'mode_id', 'label' => 'batch(es)'],
                ['table' => 'bookings', 'column' => 'mode_id', 'label' => 'booking(s)'],
            ],
            'columns'    => ['name' => 'Name', 'code' => 'Code', 'description' => 'Description', 'is_active' => 'Active'],
            'fields'     => [
                ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'rules' => 'required|max:80', 'placeholder' => 'e.g. Online (Zoom)'],
                ['name' => 'code', 'label' => 'Code', 'type' => 'text', 'rules' => 'required|max:30', 'unique' => true, 'hint' => 'online, offline or hybrid'],
                ['name' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'default' => 0],
                ['name' => 'is_active', 'label' => 'Active', 'type' => 'checkbox', 'checkbox_label' => 'Selectable', 'default' => 1],
            ],
        ];
    }
}
