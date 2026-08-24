<?php
declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Request;
use App\Models\Curriculum;

final class HomeController extends SiteController
{
    public function index(Request $request): void
    {
        $this->render('site/home', [
            'activeNav'  => 'home',
            'curricula'  => (new Curriculum())->active(),
            'metaTitle'  => 'Maytrix Education — IB · IB MYP · IGCSE · A Level Maths & Physics Tutoring',
        ]);
    }
}
