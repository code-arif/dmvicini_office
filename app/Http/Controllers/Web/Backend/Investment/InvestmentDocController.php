<?php

namespace App\Http\Controllers\Web\Backend\Investment;

use App\Helper\Helper;
use Illuminate\Http\Request;
use App\Models\InvestmentImage;
use App\Models\InvestmentDocument;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class InvestmentDocController extends Controller
{
    /**
     * Store investment document
     */
    public function uploadDocument(Request $request, $investment_id)
    {
        Log::info('Document upload attempt', [
            'investment_id' => $investment_id,
            'has_name' => $request->has('name'),
            'has_file' => $request->hasFile('file')
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,xlsx,ppt,pptx|max:20480',
        ]);

        try {
            $path = Helper::fileUpload(
                $request->file('file'),
                'investment/documents',
                time() . '_' . $request->name
            );

            $doc = InvestmentDocument::create([
                'investment_id' => $investment_id,
                'name' => $request->name,
                'file_path' => $path
            ]);

            Log::info('Document uploaded successfully', ['doc_id' => $doc->id]);

            return response()->json([
                'success' => true,
                'document' => $doc,
                'message' => 'Document uploaded successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Document upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload document',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete document
     */
    public function deleteDocument($id)
    {
        try {
            $doc = InvestmentDocument::findOrFail($id);

            // Delete file from storage
            if ($doc->file_path && file_exists(public_path($doc->file_path))) {
                @unlink(public_path($doc->file_path));
            }

            $doc->delete();

            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete document'
            ], 500);
        }
    }

    /**
     * Upload investment images
     */
    public function uploadImage(Request $request, $investment_id)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120'
        ]);

        $uploaded = [];
        foreach ($request->file('images') as $image) {
            $path = Helper::fileUpload($image, 'investment/gallery', time() . '_' . $image->getClientOriginalName());
            $uploaded[] = InvestmentImage::create([
                'investment_id' => $investment_id,
                'image_url'     => $path
            ]);
        }

        return response()->json([
            'success' => true,
            'images' => $uploaded,
            'message' => 'Images uploaded!'
        ]);
    }

    /**
     * Delete investment image
     */
    public function deleteImage($id)
    {
        try {
            $img = InvestmentImage::findOrFail($id);

            // Delete file from storage
            if ($img->image_url && file_exists(public_path($img->image_url))) {
                @unlink(public_path($img->image_url));
            }

            $img->delete();

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image'
            ], 500);
        }
    }
}
