<?php

namespace App\Http\Controllers\Api;

use App\Models\CMS;
use App\Models\Faq;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HelpcenterPageController extends Controller
{
    use ApiResponse;
    //get faqs
    public function getFaqs()
    {
        $faqs = Faq::latest()->get();

        if (!$faqs) {
            return $this->error([], 'Faq not found.', 404);
        }

        return $this->success($faqs, 'Faq retrieve successfully.', 200);
    }

    //help center hero section data
    public function helpCenterHero(){
        //get hero section's data
        $data = CMS::where('page', 'help-center-page')->where('section', 'hero')->select('page', 'section', 'title', 'description')->first();
        return $this->success($data, 'Data get successfully.', 200);
    }
}
