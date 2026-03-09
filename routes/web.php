<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminCourseController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentDocumentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CourseController::class, 'index'])->name('home');
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/course/{course}', [CourseController::class, 'show'])->name('courses.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [CourseController::class, 'dashboard'])->name('dashboard');
    Route::post('/panier/{course}', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/panier/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payment', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}/proof', [PaymentController::class, 'showProof'])->name('payments.proof');
    Route::get('/my-courses', [CourseController::class, 'myCourses'])->name('courses.my');
    Route::get('/my-courses/{course}', [CourseController::class, 'watch'])->name('courses.watch');
    Route::get('/my-courses/{course}/download', [CourseController::class, 'download'])->name('courses.download');
    Route::get('/courses/{course}/documents/{document}/download', [StudentDocumentController::class, 'download'])
        ->name('courses.documents.download');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::resource('/courses', AdminCourseController::class)->except('show');
        Route::post('/courses/{course}/documents', [DocumentController::class, 'store'])->name('courses.documents.store');
        Route::get('/payments', [AdminController::class, 'payments'])->name('payments.index');
        Route::patch('/payments/{payment}', [AdminController::class, 'updatePaymentStatus'])->name('payments.update');
        Route::get('/payments/{payment}/proof', [AdminController::class, 'showPaymentProof'])->name('payments.proof');
        Route::get('/students', [AdminController::class, 'students'])->name('students.index');
    });

require __DIR__.'/auth.php';
