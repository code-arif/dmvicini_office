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
            'slogan_line1' => 'string|max:255',
            'slogan_line2' => 'string|max:255',
            'subscribe_title' => 'string|max:255',
            'subscribe_description' => 'string',
            'copyright' => 'string|max:255',
            'disclaimer' => 'string',

            // Social Links
            'social_links' => 'array|min:1',
            'social_links.*.platform' => 'in:linkedin,tiktok,youtube,medium,facebook,instagram,twitter,x',
            'social_links.*.url' => 'url',
            'social_links.*.icon' => 'nullable|image|mimes:png,svg,jpg,jpeg,webp|max:1024',

            // Footer Links
            'footer_links' => 'array|min:1',
            'footer_links.*.title' => 'string|max:100',
            'footer_links.*.url' => 'string|max:255',
        ]);

        try {
            $footer = Footer::first() ?? new Footer();

            $data = $request->only([
                'slogan_line1',
                'slogan_line2',
                'subscribe_title',
                'subscribe_description',
                'copyright',
                'disclaimer',
            ]);

            // Handle Logo
            if ($request->hasFile('logo')) {
                if ($footer->logo) {
                    Helper::deleteImage($footer->logo);
                }
                $data['logo'] = Helper::uploadImage($request->file('logo'), 'footer/logo');
            }

            // Handle Social Icons
            $socialLinks = [];
            foreach ($request->social_links as $index => $link) {
                $iconPath = $link['icon'] ?? null;
                if ($request->hasFile("social_links.$index.icon")) {
                    $iconPath = Helper::uploadImage(
                        $request->file("social_links.$index.icon"),
                        "footer/social/{$link['platform']}"
                    );
                }
                $socialLinks[] = [
                    'platform' => $link['platform'],
                    'url' => $link['url'],
                    'icon' => $iconPath,
                ];
            }
            $data['social_links'] = $socialLinks;

            // Handle Footer Links
            $footerLinks = [];
            foreach ($request->footer_links as $link) {
                $footerLinks[] = [
                    'title' => $link['title'],
                    'url' => $link['url'],
                ];
            }
            $data['footer_links'] = $footerLinks;

            $footer->fill($data)->save();

            return redirect()->back()->with('success', 'Footer updated successfully!');
        } catch (\Exception $e) {
            Log::error('Footer Update Failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update footer. Please try again.');
        }
    }
}
