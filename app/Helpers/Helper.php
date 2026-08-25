<?php

use Carbon\Carbon;

if (!function_exists('thaidate')) {
    function thaidate($date, $format = 'full')
    {
        // 1. ถ้าไม่มีค่าวันที่ส่งมา ให้คืนค่าเป็นขีด "-"
        if (empty($date)) {
            return "-";
        }

        // 2. กำหนดชื่อเดือนภาษาไทย (ตัวย่อ)
        $months = [
            1 => 'ม.ค.',
            2 => 'ก.พ.',
            3 => 'มี.ค.',
            4 => 'เม.ย.',
            5 => 'พ.ค.',
            6 => 'มิ.ย.',
            7 => 'ก.ค.',
            8 => 'ส.ค.',
            9 => 'ก.ย.',
            10 => 'ต.ค.',
            11 => 'พ.ย.',
            12 => 'ธ.ค.'
        ];

        // ชื่อเดือนเต็ม (สำหรับหนังสือราชการ)
        $fullMonths = [
            1 => 'มกราคม',
            2 => 'กุมภาพันธ์',
            3 => 'มีนาคม',
            4 => 'เมษายน',
            5 => 'พฤษภาคม',
            6 => 'มิถุนายน',
            7 => 'กรกฎาคม',
            8 => 'สิงหาคม',
            9 => 'กันยายน',
            10 => 'ตุลาคม',
            11 => 'พฤศจิกายน',
            12 => 'ธันวาคม'
        ];

        try {
            // 3. แปลงวันที่ และบังคับ Timezone เป็นไทย (Asia/Bangkok) เสมอ
            $dateObj = \Carbon\Carbon::parse($date)->setTimezone('Asia/Bangkok');

            $day = $dateObj->format('j');           // วันที่ (ไม่มีเลข 0 นำหน้า)
            $month = $months[(int)$dateObj->format('n')]; // ดึงชื่อเดือนย่อจาก Array
            $fullMonth = $fullMonths[(int)$dateObj->format('n')]; // ดึงชื่อเดือนเต็ม
            $year = $dateObj->year + 543;           // แปลงเป็น พ.ศ.
            $time = $dateObj->format('H:i');        // เวลาแบบ 24 ชั่วโมง

            // 4. เลือกรูปแบบการแสดงผล
            if ($format === 'short') {
                // แบบย่อ: 17 ก.พ. 2569
                return "$day $month $year";
            }

            if ($format === 'official') {
                // แบบหนังสือราชการ: 25 สิงหาคม 2569
                return "$day $fullMonth $year";
            }

            if ($format === 'thai_official') {
                // แบบหนังสือราชการเลขไทย: ๒๕ สิงหาคม ๒๕๖๙
                return thainum($day) . " $fullMonth " . thainum($year);
            }

            // แบบเต็ม (ค่าเริ่มต้น): 17 ก.พ. 2569 เวลา 09:56 น.
            return "$day $month $year เวลา $time น.";
        } catch (\Exception $e) {
            // กรณี Format วันที่ผิดเพี้ยนจนแปลงไม่ได้ ให้คืนค่าเดิมกลับไป
            return $date;
        }
    }
}

if (!function_exists('thainum')) {
    function thainum($number)
    {
        $arabic = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $thai   = ['๐', '๑', '๒', '๓', '๔', '๕', '๖', '๗', '๘', '๙'];
        return str_replace($arabic, $thai, (string)$number);
    }
}

if (!function_exists('law_type')) {
    function law_type($type)
    {
        $types = [
            1 => 'ตรวจสอบข้อเท็จจริง (ตส.)',
            2 => 'สอบสวนความรับผิดทางละเมิด (สล.)',
            3 => 'สอบสวนวินัย (สว.)'
        ];

        return $types[$type] ?? 'ไม่ระบุประเภท';
    }
}

if (!function_exists('app_version')) {
    function app_version()
    {
        $versionFile = base_path('version.json');
        if (file_exists($versionFile)) {
            $data = json_decode(file_get_contents($versionFile), true);
            if (!empty($data['version'])) {
                return 'v' . ltrim($data['version'], 'v');
            }
        }
        return 'v1.0.0';
    }
}
