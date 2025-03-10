<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\RequestController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Routes untuk tampilan utama
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/community', function () {
    return view('community');
});

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');


Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', [FeedbackController::class, 'index']); // Form user
Route::post('/contact', [FeedbackController::class, 'store'])->name('feedback.store'); // Simpan feedback

Route::get('/disNetwork', [RequestController::class, 'showUserAppliedCompanies'])->name('distributionNetwork');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login'); // Rute untuk menampilkan form login
Route::post('/login', [AuthController::class, 'login'])->name('login.submit'); // Rute untuk memproses login

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register'); // Rute untuk menampilkan form registrasi
Route::post('/register', [AuthController::class, 'register'])->name('register.submit'); // Rute untuk memproses registrasi

// Rute untuk apply dengan middleware auth
Route::get('/apply', function () {
    return view('apply');
})->middleware('auth')->name('apply');
Route::post('/apply', [ApplyController::class, 'submit'])->name('apply.submit');


// Rute untuk logout
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/specification', function () {
    return view('specification'); 
});

Route::middleware(['auth', 'userAkses:admin'])->group(function () {

    //route base
    Route::get('/admin/base', [AdminController::class, 'admin'])->name('admin.base');

    // route curd berita
    Route::get('/admin/tabelBerita', [AdminController::class, 'newsTable'])->name('admin.newsTable');
    Route::get('/admin/tambahBerita', [AdminController::class, 'addNews'])->name('admin.addNews');
    Route::post('/admin/storeNews', [AdminController::class, 'storeNews'])->name('admin.storeNews');
    Route::post('/admin/news', [NewsController::class, 'store'])->name('admin.news.store');
    Route::put('/admin/news/{id}', [AdminController::class, 'updateNews'])->name('admin.news.update');
    Route::delete('/admin/news/{id}', [AdminController::class, 'destroyNews'])->name('admin.news.destroy');

    // User Request
    Route::get('/request', [RequestController::class, 'showUserRequest'])->name('user.request');
    Route::post('/request/submit', [RequestController::class, 'submitRequest'])->name('user.request.submit');
    // Admin Request
    Route::get('/admin/request', [RequestController::class, 'showAdminRequest'])->name('admin.request');
    Route::get('/admin/request/{id}/detail', [RequestController::class, 'showRequestDetail'])->name('admin.request.detail');
    Route::post('/admin/request/{id}/verify', [RequestController::class, 'verifyRequest'])->name('admin.request.verify');

    // Delete PT
    Route::delete('/admin/company/{id}', [RequestController::class, 'deleteCompany'])->name('admin.delete');


    // Rute untuk menampilkan daftar perusahaan yang diterima (status 'approved')
    Route::get('/admin/companyList', [RequestController::class, 'showAcceptedRequests'])->name('admin.companyList');
    
    // Rute untuk menampilkan detail perusahaan yang diterima
    Route::get('/admin/companyList/{id}/detail', [RequestController::class, 'showCompanyDetail'])->name('admin.detail');

    // Rute Feedback
    Route::get('/admin/feedback', [FeedbackController::class, 'adminIndex'])->name('admin.feedback');
    Route::get('/admin/feedback/{id}', [FeedbackController::class, 'detail'])->name('admin.feedback.detail');
    Route::delete('/admin/feedback/{id}', [FeedbackController::class, 'destroy'])->name('admin.feedback.destroy');
});

Route::get('gambar/{filename}', function ($filename) {
    $path = storage_path('app/public/gambar/' . $filename);

    if (file_exists($path)) {
        return response()->file($path);
    }

    abort(404);
});
