<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Mode;
use App\Models\Enrolment;

final class BatchesController extends ResourceController
{
    protected function resource(): array
    {
        return [
            'model'      => Batch::class,
            'table'      => 'batches',
            'route'      => 'batches',
            'title'      => 'Batches',
            'singular'   => 'Batch',
            'activeMenu' => 'batches',
            'order'      => 'start_date DESC, id DESC',
            'references' => [
                ['table' => 'enrolments', 'column' => 'batch_id', 'label' => 'enrolment(s)'],
            ],
            'columns'    => ['name' => 'Batch', 'status' => 'Status', 'is_published' => 'Live'],
            'fields'     => [
                ['name' => 'name', 'label' => 'Batch name', 'type' => 'text', 'rules' => 'required|max:190', 'placeholder' => 'e.g. IB HL Mathematics AA — Calculus Focus'],
                ['name' => 'course_id', 'label' => 'Course', 'type' => 'select-int', 'options_key' => 'courses', 'rules' => 'required'],
                ['name' => 'mode_id', 'label' => 'Mode', 'type' => 'select-int', 'options_key' => 'modes', 'nullable' => true, 'hint' => 'Online (Zoom), Offline (in-person) or Hybrid'],
                ['name' => 'tutor_name', 'label' => 'Tutor', 'type' => 'text', 'rules' => 'max:120'],
                ['name' => 'start_date', 'label' => 'Start date', 'type' => 'date'],
                ['name' => 'end_date', 'label' => 'End date', 'type' => 'date'],
                ['name' => 'schedule_text', 'label' => 'Schedule', 'type' => 'text', 'rules' => 'max:190', 'placeholder' => 'e.g. Tue & Thu, 6:00 PM IST'],
                ['name' => 'duration_text', 'label' => 'Duration', 'type' => 'text', 'rules' => 'max:120', 'placeholder' => 'e.g. 8 weeks'],
                ['name' => 'timezone', 'label' => 'Time zone', 'type' => 'text', 'rules' => 'max:60'],
                ['name' => 'max_seats', 'label' => 'Maximum seats', 'type' => 'number', 'rules' => 'required|int|min:1', 'default' => 6],
                ['name' => 'price', 'label' => 'Price', 'type' => 'number', 'step' => '0.01'],
                ['name' => 'currency', 'label' => 'Currency', 'type' => 'select', 'options' => ['INR' => 'INR', 'USD' => 'USD', 'GBP' => 'GBP', 'EUR' => 'EUR', 'AED' => 'AED'], 'default' => 'INR'],
                ['name' => 'mode_detail', 'label' => 'Location / platform note', 'type' => 'text', 'rules' => 'max:255', 'hint' => 'For offline: venue address. For online: any note.'],
                ['name' => 'zoom_link', 'label' => 'Zoom / class link', 'type' => 'text', 'rules' => 'max:500', 'hint' => 'Paste the Zoom meeting link — shared automatically with confirmed students.'],
                ['name' => 'meeting_notes', 'label' => 'Meeting notes', 'type' => 'textarea'],
                ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['draft' => 'Draft', 'open' => 'Open (accepting)', 'full' => 'Full', 'closed' => 'Closed', 'completed' => 'Completed'], 'default' => 'open'],
                ['name' => 'is_published', 'label' => 'Published', 'type' => 'checkbox', 'checkbox_label' => 'Visible on website', 'default' => 1],
            ],
        ];
    }

    protected function options(): array
    {
        return [
            'courses' => $this->optionMap(new Course(), 'title', 'title ASC'),
            'modes'   => $this->optionMap(new Mode(), 'name', 'sort_order ASC'),
        ];
    }

    /** Custom list with seat bars + manage links. */
    public function index(Request $request): void
    {
        $all   = (new Batch())->allWithDetails();
        $pager = new \App\Core\Paginator(count($all), \App\Core\Paginator::currentPage(), 20);
        $this->renderAdmin('admin/batches/index', [
            'pageTitle'  => 'Batches',
            'activeMenu' => 'batches',
            'batches'    => $pager->slice($all),
            'pager'      => $pager,
        ]);
    }

    /** Manage a batch's enrolments (confirm/cancel → seats + Zoom sharing). */
    public function manage(Request $request, string $id): void
    {
        $batch = (new Batch())->findWithDetails((int) $id);
        if (!$batch) {
            \App\Core\Flash::error('Batch not found.');
            $this->redirect(admin_url('batches'));
            return;
        }
        $this->renderAdmin('admin/batches/manage', [
            'pageTitle'  => 'Manage: ' . $batch['name'],
            'activeMenu' => 'batches',
            'batch'      => $batch,
            'enrolments' => (new Enrolment())->forBatch((int) $id),
        ]);
    }
}
