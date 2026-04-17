<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\RekapDiagnosaController;
use App\Http\Controllers\PenyakitController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Icd10Controller;
use App\Http\Controllers\CdiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\DiagnosaPKMController;
use App\Http\Controllers\PasienSyncController;
use App\Http\Controllers\SurvailanceController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');
    Route::post('/login', [AuthController::class, 'login']);
});

// Root route - redirect to dashboard if authenticated, welcome if not
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('root');

// Test Routes (no auth for debugging)
Route::get('/test-form', [TestController::class, 'testForm'])->name('test.form');
Route::post('/test-submit', [TestController::class, 'testSubmit'])->name('test.submit');

// Download Template PKM (No Auth Required)
Route::get('/pasien/download-template-pkm', [PasienController::class, 'downloadTemplatePKM'])->name('pasien.download-template-pkm');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Data Pasien Routes
    Route::prefix('pasien')->name('pasien.')->group(function () {
        Route::get('/', [PasienController::class, 'index'])->name('index');
        Route::get('/create', [PasienController::class, 'create'])->name('create');
        Route::get('/import', [PasienController::class, 'importPage'])->name('import');
        Route::post('/', [PasienController::class, 'store'])->name('store');
        Route::get('/{id}', [PasienController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PasienController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PasienController::class, 'update'])->name('update');
        Route::delete('/{id}', [PasienController::class, 'destroy'])->name('destroy');
        Route::post('/import-excel', [PasienController::class, 'importExcel'])->name('import-excel');
        Route::post('/import-to-database', [PasienController::class, 'importToDatabase'])->name('import-to-database');
        Route::post('/blast-data', [PasienController::class, 'blastData'])->name('blast-data');
        Route::post('/import-pkm', [PasienController::class, 'importPKM'])->name('import-pkm');
                
        // Sync Routes
        Route::get('/sync', [PasienSyncController::class, 'index'])->name('sync.index');
        Route::post('/sync/all', [PasienSyncController::class, 'syncAll'])->name('sync.all');
        Route::post('/sync/{noRekamMedik}', [PasienSyncController::class, 'sync'])->name('sync.single');
        Route::get('/sync/patients', [PasienSyncController::class, 'getSyncablePatients'])->name('sync.patients');
        Route::delete('/sync/{noRekamMedik}', [PasienSyncController::class, 'deleteSynced'])->name('sync.delete');
    });
    
    // Survailance Routes
    Route::prefix('survailance')->name('survailance.')->group(function () {
        Route::get('/', [SurvailanceController::class, 'index'])->name('dashboard');
        Route::get('/chart-data', [SurvailanceController::class, 'getChartData'])->name('chart-data');
        Route::get('/top-diseases', [SurvailanceController::class, 'getTopDiseases'])->name('top-diseases');
        Route::get('/disease-details', [SurvailanceController::class, 'getDiseaseDetails'])->name('disease-details');
        Route::post('/update-disease', [SurvailanceController::class, 'updateDisease'])->name('update-disease');
    });
    
    // Rekap Diagnosa Routes
    Route::prefix('rekap-diagnosa')->name('rekap-diagnosa.')->group(function () {
        Route::get('/', [RekapDiagnosaController::class, 'index'])->name('index');
    });
    
    // Poli Management Routes
    Route::prefix('poli')->name('poli.')->group(function () {
        Route::get('/', [PoliController::class, 'index'])->name('index');
        Route::get('/create', [PoliController::class, 'create'])->name('create');
        Route::post('/', [PoliController::class, 'store'])->name('store');
        Route::get('/{id}', [PoliController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PoliController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PoliController::class, 'update'])->name('update');
        Route::delete('/{id}', [PoliController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [PoliController::class, 'toggleStatus'])->name('toggle-status');
    });
    
        
    // Laporan Routes
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/create', [LaporanController::class, 'create'])->name('create');
        Route::post('/', [LaporanController::class, 'store'])->name('store');
        Route::get('/{id}', [LaporanController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [LaporanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [LaporanController::class, 'update'])->name('update');
        Route::delete('/{id}', [LaporanController::class, 'destroy'])->name('destroy');
        Route::get('/export', [LaporanController::class, 'export'])->name('export');
        Route::get('/analisis', [LaporanController::class, 'analisis'])->name('analisis');
    });
    
    // User Management Routes
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('/roles', [UserController::class, 'roles'])->name('roles');
        Route::post('/roles', [UserController::class, 'storeRole'])->name('roles.store');
        Route::put('/roles/{id}', [UserController::class, 'updateRole'])->name('roles.update');
        Route::delete('/roles/{id}', [UserController::class, 'deleteRole'])->name('roles.delete');
        Route::get('/roles/{id}/permissions', [UserController::class, 'getRolePermissions'])->name('roles.permissions');
        Route::post('/roles/{id}/permissions', [UserController::class, 'assignRolePermissions'])->name('roles.permissions.assign');
        Route::get('/roles/{id}/users', [UserController::class, 'getRoleUsers'])->name('roles.users');
        Route::post('/{id}/assign-role', [UserController::class, 'assignRole'])->name('assign-role');
        Route::post('/{id}/assign-permission', [UserController::class, 'assignPermission'])->name('assign-permission');
    });
    
    // Roles Management Route (standalone)
    Route::get('/roles', [UserController::class, 'roles'])->name('roles');
    
    // Permission Management Routes
    Route::get('/permissions', [UserController::class, 'permissions'])->name('permissions');
    Route::post('/permissions', [UserController::class, 'storePermission'])->name('permissions.store');
    Route::put('/permissions/{id}', [UserController::class, 'updatePermission'])->name('permissions.update');
    Route::delete('/permissions/{id}', [UserController::class, 'deletePermission'])->name('permissions.delete');
    Route::post('/permission-groups', [UserController::class, 'storePermissionGroup'])->name('permission-groups.store');
    Route::put('/permission-groups/{id}', [UserController::class, 'updatePermissionGroup'])->name('permission-groups.update');
    Route::delete('/permission-groups/{id}', [UserController::class, 'deletePermissionGroup'])->name('permission-groups.delete');
    
        
    // Debug Route
    Route::get('/debug-user', function () {
        if (!auth()->check()) {
            return 'Not authenticated';
        }
        
        $user = auth()->user();
        return [
            'user' => $user->name,
            'email' => $user->email,
            'roles' => $user->roles->pluck('name')->toArray(),
            'has_superadmin_role' => $user->hasRole('superadmin'),
            'permissions' => $user->getAllPermissions()->pluck('name')->take(10)->toArray(),
            'has_read_cdi' => $user->hasPermission('read-cdi'),
        ];
    });
    
    // ICD-10 Codes Routes
    Route::prefix('icd10')->name('icd10.')->group(function () {
        Route::get('/', [ICD10Controller::class, 'index'])->name('index');
        Route::get('/create', [ICD10Controller::class, 'create'])->name('create');
        Route::post('/', [ICD10Controller::class, 'store'])->name('store');
        Route::get('/{id}', [ICD10Controller::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ICD10Controller::class, 'edit'])->name('edit');
        Route::put('/{id}', [ICD10Controller::class, 'update'])->name('update');
        Route::delete('/{id}', [ICD10Controller::class, 'destroy'])->name('destroy');
        
        // API Routes for Satu Sehat Integration
        Route::get('/search-api', [ICD10Controller::class, 'searchFromAPI'])->name('search-api');
        Route::post('/import-api', [ICD10Controller::class, 'importFromAPI'])->name('import-api');
    });
    
    // AI Assistant Routes
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::get('/dashboard', [AIController::class, 'index'])->name('dashboard');
    });
    
    // Diagnosa PKM Routes
    Route::prefix('diagnosa-pkm')->name('diagnosa-pkm.')->group(function () {
        Route::get('/', [DiagnosaPKMController::class, 'index'])->name('index');
        Route::get('/import', [DiagnosaPKMController::class, 'import'])->name('import');
        Route::post('/import', [DiagnosaPKMController::class, 'import_proses'])->name('import.proses');
        Route::get('/download-template', [DiagnosaPKMController::class, 'download_template'])->name('download.template');
        Route::get('/{id}', [DiagnosaPKMController::class, 'show'])->name('show');
        Route::delete('/{id}', [DiagnosaPKMController::class, 'destroy'])->name('destroy');
    });
});
