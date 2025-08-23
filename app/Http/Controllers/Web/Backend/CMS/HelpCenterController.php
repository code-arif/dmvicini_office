<?php

namespace App\Http\Controllers\Web\Backend\CMS;

use Exception;
use App\Models\CMS;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CMS\CmsManageRequest;

class HelpCenterController extends Controller
{
    //show help center page
    public function helpCenterPage()
    {
        $data = CMS::where('page', 'help-center-page')->where('section', 'hero')->first();
        return view('backend.layouts.cms.help-center.help-center-hero',  compact("data"));
    }

    /**
     * update event hero section
     **/
    public function updateHero(CmsManageRequest $request)
    {
        // dd($request->all());
        try {
            $validated_data = $request->validated();

            // get the existing record
            $existing = CMS::where('page', 'help-center-page')
                ->where('section', 'hero')
                ->first();

            CMS::updateOrCreate(
                [
                    'page' => 'help-center-page',
                    'section' => 'hero',
                ],
                $validated_data
            );

            return back()->with('t-success', 'Hero content updated successfully!');
        } catch (Exception $e) {
            return back()->with('t-error', 'Failed to update: ' . $e->getMessage());
        }
    }
}
