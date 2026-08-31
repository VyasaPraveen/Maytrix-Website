<?php
declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Request;
use App\Models\Curriculum;
use App\Models\Post;

final class HomeController extends SiteController
{
    public function index(Request $request): void
    {
        $this->render('site/home', [
            'activeNav'  => 'home',
            'curricula'  => (new Curriculum())->active(),
            'posts'      => array_slice((new Post())->published(), 0, 3),
            'metaTitle'  => 'Maytrix Education — IB · IBMYP · IGCSE · A Level Maths & Physics Tutoring',
            'metaDescription' => 'Specialist online 1-to-1 and small-group tuition in Mathematics & Physics for IB, IBMYP, Cambridge IGCSE and AS & A Level — taught to your exact exam board and mark scheme.',
        ]);
    }
}
