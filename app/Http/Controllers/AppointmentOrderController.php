<?php

namespace App\Http\Controllers;

use App\Models\AppointmentOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AppointmentOrderController extends Controller
{
    /**
     * แสดงรายการคำสั่ง (แฟ้มคำสั่ง)
     */
    public function index()
    {
        $orders = AppointmentOrder::orderBy('order_date', 'desc')->paginate(15);
        return view('law.index_orders', compact('orders'));
    }

    /**
     * แสดงหน้าฟอร์มเพิ่มข้อมูล
     */
    public function create()
    {
        return view('law.create_order');
    }

    /**
     * บันทึกข้อมูลและไฟล์แนบ
     */
    public function store(Request $request)
    {
        // ขั้นแรก ตรวจสอบรูปแบบข้อมูลพื้นฐาน
        $validated = $request->validate([
            'order_number' => 'required|string|max:255',
            'order_date'   => 'required|date',
            'subject'      => 'required|string|max:500',
            'to'           => 'nullable|string|max:255',
            'description'  => 'nullable|string',
            'owner'        => 'required|string|max:255',
            'status'       => 'required|string',
            'attachment'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB
        ]);

        // ป้องกันการใช้เลขคำสั่งเดิมในปีปัจจุบัน
        // (ใช้ปีปัจจุบันแทนการดึงจาก `order_date` เพื่อให้สอดคล้องกับนโยบาย)
        $year = Carbon::now()->year;
        $duplicate = AppointmentOrder::where('order_number', $validated['order_number'])
            ->whereYear('order_date', $year)
            ->exists();

        if ($duplicate) {
            $thaiYear = $year + 543; // แปลงเป็น พ.ศ.
            return back()
                ->withErrors(['order_number' => 'เลขที่คำสั่งนี้มีอยู่แล้วในปี ' . $thaiYear])
                ->withInput();
        }

        try {
            // แก้ไข: แยกรายการฟิลด์ให้ถูกต้อง (ก่อนหน้านี้มีพิมพ์ผิด 'to, description')
            $data = $request->only(['order_number', 'order_date', 'subject', 'owner', 'status', 'to', 'description']);

            // จัดการอัปโหลดไฟล์
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                // ตั้งชื่อไฟล์เพื่อไม่ให้ซ้ำกัน
                $fileName = time() . '_' . $file->getClientOriginalName();
                // เก็บใน storage/app/public/orders
                $path = $file->storeAs('orders', $fileName, 'public');
                $data['file_path'] = $path;
            }

            $newOrder = AppointmentOrder::create($data);

            try {
                broadcast(new \App\Events\OrderCreated($newOrder));
            } catch (\Throwable $e) {
                \Log::warning('Broadcast OrderCreated failed: ' . $e->getMessage());
            }

            try {
                \App\Services\TelegramService::notifyOrderCreated($newOrder);
            } catch (\Throwable $e) {
                \Log::warning('Telegram notifyOrderCreated failed: ' . $e->getMessage());
            }

            return redirect()->route('orders.index')->with('success', 'บันทึกคำสั่งแต่งตั้งเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * ส่งออกทะเบียนคำสั่งแต่งตั้งเป็นไฟล์ Excel (CSV UTF-8 BOM)
     */
    public function export(Request $request)
    {
        $orders = AppointmentOrder::orderBy('order_date', 'desc')->get();
        $filename = 'appointment_orders_export_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'ลำดับ',
                'เลขที่คำสั่ง',
                'วันที่คำสั่ง',
                'เรื่อง',
                'ถึง/ผู้เกี่ยวข้อง',
                'ผู้ลงนาม/ผู้รับผิดชอบ',
                'สถานะ',
                'วันที่บันทึก',
            ]);

            foreach ($orders as $index => $o) {
                fputcsv($file, [
                    $index + 1,
                    $o->order_number,
                    $o->order_date ? \Carbon\Carbon::parse($o->order_date)->format('Y-m-d') : '-',
                    $o->subject,
                    $o->to ?? '-',
                    $o->owner ?? '-',
                    $o->status ?? '-',
                    $o->created_at ? $o->created_at->format('Y-m-d H:i') : '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * ดาวน์โหลดไฟล์แนบ
     */
    public function download($id)
    {
        $order = AppointmentOrder::findOrFail($id);

        if ($order->file_path && Storage::disk('public')->exists($order->file_path)) {
            return Storage::disk('public')->download($order->file_path);
        }

        return back()->with('error', 'ไม่พบไฟล์ต้นฉบับในระบบ');
    }

    /**
     * ตรวจสอบเลขคำสั่งซ้ำ (AJAX)
     */
    public function checkDuplicate(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
            'order_date'   => 'required|date',
            'exclude_id'   => 'nullable|integer',
        ]);

        // ตรวจสอบเฉพาะปีปัจจุบัน (ไม่สนใจค่า order_date ที่ผู้ใช้ส่งมา)
        $year = Carbon::now()->year;

        $query = AppointmentOrder::where('order_number', $request->order_number)
            ->whereYear('order_date', $year);

        if ($request->filled('exclude_id')) {
            $query->where('id', '<>', $request->exclude_id);
        }

        return response()->json([
            'duplicate' => $query->exists(),
            'year' => $year + 543, // ส่งเป็น พ.ศ. (ตัวเลข) ให้ client แสดงผลได้ตรง
        ]);
    }

    public function viewPdf($id)
    {
        $order = AppointmentOrder::findOrFail($id);

        // ตรวจสอบว่ามีไฟล์จริงและเป็น PDF หรือไม่ (ใช้ file_path ซึ่งเป็นคอลัมน์จริง)
        if (
            $order->file_path && Storage::disk('public')->exists($order->file_path)
            && strtolower(pathinfo($order->file_path, PATHINFO_EXTENSION)) === 'pdf'
        ) {

            // ดึง Path จริงของไฟล์ในเครื่อง Server (Absolute Path)
            $path = Storage::disk('public')->path($order->file_path);

            // ส่งไฟล์กลับไปแบบ 'inline' (เปิดใน Browser)
            return response()->file($path, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Order-' . $order->order_number . '.pdf"'
            ]);
        }

        return back()->with('error', 'ไม่พบไฟล์ PDF ในระบบ');
    }

    public function edit($id)
    {
        $order = AppointmentOrder::findOrFail($id);
        return view('law.edit_order', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = AppointmentOrder::findOrFail($id);

        $validated = $request->validate([
            'order_number' => 'required|string|max:255',
            'order_date'   => 'required|date',
            'subject'      => 'required|string|max:500',
            'to'           => 'nullable|string|max:255',
            'description'  => 'nullable|string',
            'owner'        => 'required|string|max:255',
            'status'       => 'required|string',
            'attachment'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB
        ]);

        // ป้องกันการใช้เลขคำสั่งเดิมในปีปัจจุบัน (ยกเว้นตัวเอง)
        $year = Carbon::now()->year;
        $duplicate = AppointmentOrder::where('order_number', $validated['order_number'])
            ->whereYear('order_date', $year)
            ->where('id', '<>', $id) // ยกเว้นตัวเอง
            ->exists();

        if ($duplicate) {
            $thaiYear = $year + 543; // แปลงเป็น พ.ศ.
            return back()
                ->withErrors(['order_number' => 'เลขที่คำสั่งนี้มีอยู่แล้วในปี ' . $thaiYear])
                ->withInput();
        }

        try {
            $data = $request->only(['order_number', 'order_date', 'subject', 'owner', 'status', 'to', 'description']);

            if ($request->hasFile('attachment')) {
                // ลบไฟล์เก่าออกก่อน (ถ้ามี)
                if ($order->file_path && Storage::disk('public')->exists($order->file_path)) {
                    Storage::disk('public')->delete($order->file_path);
                }

                // อัปโหลดไฟล์ใหม่
                $file = $request->file('attachment');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('orders', $fileName, 'public');
                $data['file_path'] = $path;
            }

            $order->update($data);

            return redirect()->route('orders.index')->with('success', 'อัปเดตคำสั่งแต่งตั้งเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $order = AppointmentOrder::findOrFail($id);

        try {
            // ลบไฟล์แนบก่อน (ถ้ามี)
            if ($order->file_path && Storage::disk('public')->exists($order->file_path)) {
                Storage::disk('public')->delete($order->file_path);
            }

            $order->delete();

            return redirect()->route('orders.index')->with('success', 'ลบคำสั่งแต่งตั้งเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }
}
