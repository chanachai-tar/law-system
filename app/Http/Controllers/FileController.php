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
        
        if (!Storage::disk('local')->exists($filePath)) {
            abort(404, 'ไม่พบไฟล์ที่ต้องการ');
        }

        return Storage::disk('local')->download($filePath);
    }

    /**
     * View/preview a case file inline.
     */
    public function view($path)
    {
        $filePath = base64_decode($path);
        
        if (!Storage::disk('local')->exists($filePath)) {
            abort(404, 'ไม่พบไฟล์ที่ต้องการ');
        }

        $mimeType = Storage::disk('local')->mimeType($filePath);
        
        return response(Storage::disk('local')->get($filePath))
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline');
    }
}
