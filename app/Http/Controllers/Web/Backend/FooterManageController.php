<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helper\Helper;
use App\Models\Footer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class FooterManageController extends Controller
{
    // Display the footer management page
    public function index()
    {
        $data = Footer::first() ?? new Footer(); // fallback if no record
        return view('backend.layouts.cms.footer.index', compact('data'));
    }

    // Handle footer update
    public function update(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'slogan_line' => 'nullable|string|max:255',
            'subscribe_title' => 'nullable|string|max:255',
            'subscribe_description' => 'nullable|string',
            'copyright' => 'nullable|string|max:255',
            'disclaimer' => 'nullable|string',

            // Social Links
            'social_links' => 'nullable|array',
            'social_links.*.platform' => 'required_with:social_links.*.url|in:linkedin,tiktok,youtube,medium,facebook,instagram,twitter,x',
            'social_links.*.url' => 'required_with:social_links.*.platform|url',
            'social_links.*.icon' => 'nullable|image|mimes:png,svg,jpg,jpeg,webp|max:1024',
        ]);

        try {
            $footer = Footer::first() ?? new Footer();

            $data = $request->only([
                'slogan_line',
                'subscribe_title',
                'subscribe_description',
                'copyright',
                'disclaimer',
            ]);

            // Handle Logo Upload
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($footer->logo) {
                    Helper::deleteImage($footer->logo);
                }
                $data['logo'] = Helper::fileUpload($request->file('logo'), 'footer/logo', time());
            }

            // Handle Social Links with Icons
            $socialLinks = [];
            if ($request->has('social_links') && is_array($request->social_links)) {
                foreach ($request->social_links as $index => $link) {
                    // Skip empty entries
                    if (empty($link['platform']) || empty($link['url'])) {
                        continue;
                    }

                    $iconPath = null;

                    // Check if new icon is uploaded
                    if ($request->hasFile("social_links.$index.icon")) {
                        $iconPath = Helper::fileUpload(
                            $request->file("social_links.$index.icon"),
                            "footer/social",
                            time() . '_' . $index
                        );
                    }
                    // Keep existing icon if available
                    elseif (!empty($link['existing_icon'])) {
                        $iconPath = $link['existing_icon'];
                    }

                    $socialLinks[] = [
                        'platform' => $link['platform'],
                        'url' => $link['url'],
                        'icon' => $iconPath,
                    ];
                }
            }
            $data['social_links'] = json_encode($socialLinks);

            // Save footer data
            $footer->fill($data)->save();

            return redirect()->back()->with('success', 'Footer updated successfully!');
        } catch (\Exception $e) {
            Log::error('Footer Update Failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update footer: ' . $e->getMessage());
        }
    }
}
