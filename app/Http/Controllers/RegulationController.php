<?php

namespace App\Http\Controllers;

use App\Models\Regulation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RegulationController extends Controller
{
    /**
     * แสดงรายการคลังระเบียบ กฎหมาย และหนังสือเวียน
     */
    public function index(Request $request)
    {
        $query = Regulation::with('user')->latest();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('file_name', 'LIKE', "%{$search}%");
            });
        }

        $regulations = $query->paginate(12)->withQueryString();

        $stats = [
            'all'                => Regulation::count(),
            'regulation'         => Regulation::where('category', 'regulation')->count(),
            'cabinet_resolution' => Regulation::where('category', 'cabinet_resolution')->count(),
            'circular_letter'    => Regulation::where('category', 'circular_letter')->count(),
            'general_law'        => Regulation::where('category', 'general_law')->count(),
        ];

        return view('law.regulations', compact('regulations', 'stats'));
    }

    /**
     * บันทึกระเบียบ/กฎหมายใหม่
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|string|in:regulation,cabinet_resolution,circular_letter,general_law',
            'description' => 'nullable|string',
            'attachment'  => 'required|file|mimes:pdf,doc,docx,zip|max:20480', // 20MB
        ]);

        $file = $request->file('attachment');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('regulations', $fileName, 'public');

        $fileSize = round($file->getSize() / 1024, 1) . ' KB';
        if ($file->getSize() > 1048576) {
            $fileSize = round($file->getSize() / 1048576, 2) . ' MB';
        }

        Regulation::create([
            'title'       => $request->title,
            'category'    => $request->category,
            'file_path'   => $filePath,
            'file_name'   => $file->getClientOriginalName(),
            'file_size'   => $fileSize,
            'description' => $request->description,
            'user_id'     => Auth::id(),
        ]);

        return redirect()->route('regulations.index')->with('success', 'บันทึกระเบียบ/หนังสือเวียนเข้าสู่คลังความรู้เรียบร้อยแล้ว');
    }

    /**
     * ลบระเบียบ/กฎหมาย
     */
    public function destroy($id)
    {
        $reg = Regulation::findOrFail($id);

        if ($reg->file_path && Storage::disk('public')->exists($reg->file_path)) {
            Storage::disk('public')->delete($reg->file_path);
        }

        $reg->delete();

        return redirect()->route('regulations.index')->with('success', 'ลบเอกสารออกจากคลังความรู้เรียบร้อยแล้ว');
    }
}
