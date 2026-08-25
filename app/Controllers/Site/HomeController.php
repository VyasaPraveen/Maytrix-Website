<?php
declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Request;
use App\Models\Curriculum;
use App\Models\Batch;
use App\Models\Post;

final class HomeController extends SiteController
{
    public function index(Request $request): void
    {
        $classes = (new Batch())->publishedWithDetails();

        $this->render('site/home', [
            'activeNav'  => 'home',
            'curricula'  => (new Curriculum())->active(),
            'classes'    => array_slice($classes, 0, 3),
            'posts'      => array_slice((new Post())->published(), 0, 3),
            'metaTitle'  => 'Maytrix Education — IB · IB MYP · IGCSE · A Level Maths & Physics Tutoring',
            'metaDescription' => 'Specialist online 1-to-1 and small-group tuition in Mathematics & Physics for IB, IB MYP, Cambridge IGCSE and AS & A Level — taught to your exact exam board and mark scheme.',
        ]);
    }
}
