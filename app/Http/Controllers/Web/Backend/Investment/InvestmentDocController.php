<?php

namespace App\Http\Controllers\Web\Backend\Investment;

use App\Helper\Helper;
use Illuminate\Http\Request;
use App\Models\InvestmentImage;
use App\Models\InvestmentDocument;
use App\Http\Controllers\Controller;

class InvestmentDocController extends Controller
{
    /**
     * Store investment media (documents + images)
     */
    public function mediaStore(Request $request)
    {
        $request->validate([
            'investment_id' => 'required|exists:investments,id',
            'documents.*'   => 'nullable|file|mimes:pdf,doc,docx,xlsx,pptx',
            'images.*'      => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
        ]);

        $investmentId = $request->investment_id;

        // Handle documents only if new ones uploaded
        if ($request->hasFile('documents')) {
            $oldDocs = InvestmentDocument::where('investment_id', $investmentId)->get();
            foreach ($oldDocs as $doc) {
                if ($doc->file_path) {
                    Helper::deleteImage($doc->file_path);
                }
                $doc->delete();
            }

            foreach ($request->file('documents') as $document) {
                $fileName = $document->getClientOriginalName();
                $filePath = $document->store('investment_documents', 'public');

                InvestmentDocument::create([
                    'investment_id' => $investmentId,
                    'name'          => $fileName,
                    'file_path'     => $filePath,
                ]);
            }
        }

        // Handle images only if new ones uploaded
        if ($request->hasFile('images')) {
            $oldImages = InvestmentImage::where('investment_id', $investmentId)->get();
            foreach ($oldImages as $img) {
                if ($img->image_url) {
                    Helper::deleteImage($img->image_url);
                }
                $img->delete();
            }

            foreach ($request->file('images') as $image) {
                // Generate a unique filename for each image
                $uniqueName = uniqid() . '_' . time();
                $path = Helper::fileUpload($image, 'investment_images', $uniqueName);

                InvestmentImage::create([
                    'investment_id' => $investmentId,
                    'image_url'     => $path,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Investment media uploaded successfully.'
        ]);
    }
}
