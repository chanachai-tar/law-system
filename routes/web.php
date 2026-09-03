<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LegalCaseController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingUserController;
use App\Http\Controllers\AppointmentOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RegulationController;
use App\Http\Controllers\FileController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// SSO Routes (ODPC10 IDP - Single Sign-On)
Route::get('/auth/oidc/redirect', [\App\Http\Controllers\Auth\SsoController::class, 'redirect'])->name('sso.login');
Route::get('/auth/oidc/callback', [\App\Http\Controllers\Auth\SsoController::class, 'callback'])->name('sso.callback');

// 2FA Challenge Routes (ต้อง login ด้วย password ก่อน)
Route::middleware(['2fa.challenge', 'throttle:5,1'])->group(function () {
    Route::get('/auth/2fa/challenge', [\App\Http\Controllers\Auth\Google2faController::class, 'challenge'])->name('google2fa.challenge');
    Route::post('/auth/2fa/verify', [\App\Http\Controllers\Auth\Google2faController::class, 'verify'])->name('google2fa.verify');
});

// Protected Routes (ต้อง login แล้ว)
Route::middleware(['auth'])->group(function () {
    // แดชบอร์ดหลัก
    Route::get('/', [LegalCaseController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [LegalCaseController::class, 'dashboard'])->name('dashboard');

    // ส่งออกข้อมูล (Export CSV/Excel)
    Route::get('/cases/export', [LegalCaseController::class, 'export'])->name('cases.export');
    Route::get('/orders/export', [AppointmentOrderController::class, 'export'])->name('orders.export');

    // สำนวนตามสถานะ และคลังแฟ้มเอกสาร
    Route::get('/cases/pending', [LegalCaseController::class, 'pending'])->name('cases.pending');
    Route::get('/cases/completed', [LegalCaseController::class, 'completed'])->name('cases.completed');
    Route::get('/cases/files', [LegalCaseController::class, 'files'])->name('cases.files');
    Route::patch('/cases/{id}/close', [LegalCaseController::class, 'closeCase'])->name('cases.close');

    // Resource สำหรับจัดการสำนวน
    Route::resource('cases', LegalCaseController::class);

    // คลังระเบียบ กฎหมาย และหนังสือเวียน
    Route::resource('regulations', RegulationController::class)->only(['index', 'store', 'destroy']);

    // คำสั่งแต่งตั้งคณะกรรมการ
    Route::get('/orders', [AppointmentOrderController::class, 'index'])->name('orders.index');
    Route::get('/creates', [AppointmentOrderController::class, 'create'])->name('orders.create');
    Route::get('/orders/create', [AppointmentOrderController::class, 'create'])->name('orders.create.view');
    Route::post('/store', [AppointmentOrderController::class, 'store'])->name('orders.store');
    Route::post('/orders', [AppointmentOrderController::class, 'store'])->name('orders.store.view');
    Route::get('/orders/check-duplicate', [AppointmentOrderController::class, 'checkDuplicate'])->name('orders.checkDuplicate');
    Route::get('/download/{id}', [AppointmentOrderController::class, 'download'])->name('orders.download');
    Route::get('/orders/{id}/download', [AppointmentOrderController::class, 'download'])->name('orders.download.view');
    Route::get('/orders/{id}/view-pdf', [AppointmentOrderController::class, 'viewPdf'])->name('orders.viewPdf');
    Route::get('/orders/{id}/edit', [AppointmentOrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{id}', [AppointmentOrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{id}', [AppointmentOrderController::class, 'destroy'])->name('orders.destroy');

    // รายงาน
    Route::get('/report', [ReportController::class, 'index'])->name('reports.index');

    // ดาวน์โหลดไฟล์เอกสาร (ผ่าน auth)
    Route::get('/files/download/{path}', [FileController::class, 'download'])->name('files.download')->where('path', '.*');
    Route::get('/files/view/{path}', [FileController::class, 'view'])->name('files.view')->where('path', '.*');

    // 2FA Setup (ต้อง login แล้วถึงจะ setup ได้)
    Route::get('/auth/2fa/generate-setup', [\App\Http\Controllers\Auth\Google2faController::class, 'generateSetupQr'])->name('google2fa.generate_setup');
    Route::get('/auth/2fa/setup/{username}', [\App\Http\Controllers\Auth\Google2faController::class, 'setup'])->name('google2fa.setup');
    Route::post('/auth/2fa/setup/confirm', [\App\Http\Controllers\Auth\Google2faController::class, 'confirmSetup'])->name('google2fa.setup.confirm');

    // จัดการข้อมูลผู้ใช้งาน (เฉพาะ Admin)
    Route::middleware(['admin'])->group(function () {
        Route::get('/setting-user', [SettingUserController::class, 'index'])->name('users.index');
        Route::post('/setting-user', [SettingUserController::class, 'store'])->name('users.store');
        Route::put('/setting-user/{id}', [SettingUserController::class, 'update'])->name('users.update');
        Route::delete('/setting-user/{id}', [SettingUserController::class, 'destroy'])->name('users.destroy');
        Route::patch('/setting-user/{id}/toggle', [SettingUserController::class, 'toggleStatus'])->name('users.toggle');

        // ตั้งค่าการแจ้งเตือน Telegram (Admin)
        Route::get('/admin/telegram', [\App\Http\Controllers\SettingTelegramController::class, 'index'])->name('settings.telegram.index');
        Route::post('/admin/telegram', [\App\Http\Controllers\SettingTelegramController::class, 'update'])->name('settings.telegram.update');
        Route::post('/admin/telegram/test', [\App\Http\Controllers\SettingTelegramController::class, 'testNotification'])->name('settings.telegram.test');
    });
});
