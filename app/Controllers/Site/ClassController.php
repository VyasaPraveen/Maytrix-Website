<?php
declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Request;
use App\Models\Batch;

final class ClassController extends SiteController
{
    public function oneToOne(Request $request): void
    {
        $this->render('site/one-to-one', [
            'activeNav' => 'classes',
            'metaTitle' => '1-to-1 Classes | Maytrix Education',
            'metaDescription' => 'One tutor, one student, your exact syllabus — fully personalised online tutoring.',
        ]);
    }

    public function smallGroup(Request $request): void
    {
        // Only published, open batches for small-group courses.
        $batches = (new Batch())->publishedWithDetails(['status' => 'open']);
        $batches = array_filter($batches, fn($b) => $b['class_type'] === 'small_group');
        $this->render('site/small-group', [
            'activeNav' => 'classes',
            'batches'   => array_values($batches),
            'metaTitle' => 'Small-Group Classes | Maytrix Education',
            'metaDescription' => 'Cohorts capped at 6 students, matched by curriculum and level.',
        ]);
    }

    public function batch(Request $request, string $id): void
    {
        $batch = (new Batch())->findWithDetails((int) $id);
        if (!$batch || (int) ($batch['is_published'] ?? 0) !== 1) {
            http_response_code(404);
            $this->render('site/404', ['activeNav' => 'classes', 'metaTitle' => 'Batch not found']);
            return;
        }
        $this->render('site/batch', [
            'activeNav' => 'classes',
            'batch'     => $batch,
            'metaTitle' => ($batch['name'] ?? 'Batch') . ' | Maytrix Education',
            'metaDescription' => $batch['course_title'] ?? '',
        ]);
    }
}
