<?php
declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Request;
use App\Models\Curriculum;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\CurriculumPage;

final class CurriculumController extends SiteController
{
    public function curricula(Request $request): void
    {
        $this->render('site/curricula', [
            'activeNav' => 'programmes',
            'curricula' => (new Curriculum())->active(),
            'pages'     => (new CurriculumPage())->published(),
            'metaTitle' => 'Curricula — IB · IBMYP · Cambridge IGCSE · AS & A Level | Maytrix Education',
            'metaDescription' => 'Four international curricula, taught by specialists who work inside them every week.',
        ]);
    }

    public function subjects(Request $request): void
    {
        $subjects = (new Subject())->active();
        $topicModel = new Topic();
        $topicsBySubject = [];
        foreach ($subjects as $s) {
            $topicsBySubject[$s['id']] = $topicModel->forSubject((int) $s['id']);
        }
        $this->render('site/subjects', [
            'activeNav'       => 'programmes',
            'subjects'        => $subjects,
            'topicsBySubject' => $topicsBySubject,
            'pages'           => (new CurriculumPage())->published(),
            'metaTitle'       => 'Subjects — Mathematics & Physics | Maytrix Education',
            'metaDescription' => 'We teach Mathematics and Physics, deeply, across four curricula.',
        ]);
    }

    public function matrix(Request $request): void
    {
        $this->render('site/matrix', [
            'activeNav' => 'programmes',
            'matrix'    => (new CurriculumPage())->matrix(),
            'metaTitle' => 'Course Matrix | Maytrix Education',
            'metaDescription' => 'Every curriculum crossed with every subject we teach — dedicated pages for each.',
        ]);
    }

    public function show(Request $request, string $slug): void
    {
        $page = (new CurriculumPage())->bySlug($slug);
        if (!$page || (int) $page['is_published'] !== 1) {
            http_response_code(404);
            $this->render('site/404', ['activeNav' => 'programmes', 'metaTitle' => 'Page not found']);
            return;
        }
        $this->render('site/curriculum-detail', [
            'activeNav'       => 'programmes',
            'page'            => $page,
            'metaTitle'       => $page['seo_title'] ?: ($page['title'] . ' | Maytrix Education'),
            'metaDescription' => $page['seo_description'] ?: $page['intro'],
        ]);
    }
}
