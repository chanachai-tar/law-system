<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Download a case file through authenticated route.
     */
    public function download($path)
    {
        $filePath = base64_decode($path);
        
        $disk = 'local';
        if (!Storage::disk('local')->exists($filePath)) {
            if (Storage::disk('public')->exists($filePath)) {
                $disk = 'public';
            } else {
                abort(404, 'ไม่พบไฟล์ที่ต้องการ');
            }
        }

        return Storage::disk($disk)->download($filePath);
    }

    /**
     * View/preview a case file inline.
     */
    public function view($path)
    {
        $filePath = base64_decode($path);
        
        $disk = 'local';
        if (!Storage::disk('local')->exists($filePath)) {
            if (Storage::disk('public')->exists($filePath)) {
                $disk = 'public';
            } else {
                abort(404, 'ไม่พบไฟล์ที่ต้องการ');
            }
        }

        $mimeType = Storage::disk($disk)->mimeType($filePath);
        
        return response(Storage::disk($disk)->get($filePath))
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline');
    }
}
