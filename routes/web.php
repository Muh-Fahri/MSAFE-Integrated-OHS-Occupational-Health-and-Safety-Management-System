<?php

use App\Http\Controllers\Administrator\RoleController;
use App\Http\Controllers\Administrator\RolePermissionController;
use App\Http\Controllers\Administrator\UserController;
use App\Http\Controllers\Auth\MicrosoftController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\File\FileController;
use App\Http\Controllers\LockscreenController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Master\MasterCompanyController;
use App\Http\Controllers\Master\MasterDepartment;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Report\HazardReportController;
use App\Http\Controllers\Report\IncidentRreportController;
use App\Http\Controllers\Transaction\AssetController;
use App\Http\Controllers\Transaction\BadgeController;
use App\Http\Controllers\Transaction\CorrectiveActionController;
use App\Http\Controllers\Transaction\HazardController;
use App\Http\Controllers\Transaction\IncidentInvestigationController;
use App\Http\Controllers\Transaction\IncidentNotificationController;
use App\Http\Controllers\Transaction\LicenseController;
use App\Http\Controllers\Transaction\MonthlyReportController;
use App\Http\Controllers\Transaction\PersonelController;
use App\Http\Controllers\Transaction\PersonnelAssignmentController;
use App\Http\Controllers\Transaction\WorkPlaceController;
use App\Http\Controllers\Transaction\DelegationController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;

Route::get('/transaction/license/detail_qrcode/{id}', [LicenseController::class, 'detail_qrcode'])->name('license.detail_qrcode');
// Root redirect ke login
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/auth/microsoft', [MicrosoftController::class, 'redirect']);
Route::get('/auth/microsoft/callback', [MicrosoftController::class, 'callback']);

// Guest routes (login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Lockscreen route (diluar middleware auth karena user sudah di-logout)
Route::get('/lockscreen', [LockscreenController::class, 'show'])->name('lockscreen.show');
Route::post('/lockscreen/unlock', [LockscreenController::class, 'unlock'])->name('lockscreen.unlock');
Route::post('/lockscreen/logout', [LockscreenController::class, 'logout'])->name('lockscreen.logout');

// Protected routes
Route::middleware(['auth', 'check.session'])->group(function () {

    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::put('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/photo', [ProfileController::class, 'deletePhoto'])->name('profile.delete-photo');
    });

    // Authentication actions
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/lock', [LoginController::class, 'lock'])->name('lock');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Menu Management
    Route::resource('menus', MenuController::class);
    Route::post('menus/detect-actions', [MenuController::class, 'detectActions'])->name('menus.detect-actions');

    // Administrator routes
    Route::prefix('administrator')->group(function () {

        // Role Management
        Route::resource('roles', RoleController::class);

        // User Management
        Route::resource('users', UserController::class);

        // Role Permission Management
        Route::get('role-permissions', [RolePermissionController::class, 'index'])->name('role-permissions.index');
        Route::get('role-permissions/{role}', [RolePermissionController::class, 'show']);
        Route::post('role-permissions', [RolePermissionController::class, 'store'])->name('role-permissions.store');
    });

    // Report
    Route::get('report/hazard', [HazardReportController::class, 'index'])->name('report.hazard_report');
    Route::get('report/incident', [IncidentRreportController::class, 'index'])->name('report.incident_report');


    // master routes
    Route::prefix('master')->group(function () {
        // isi nanti sendiri
        Route::prefix('company')->group(function () {
            Route::get('/', [MasterCompanyController::class, 'index'])->name('master-company.index');
            Route::get('/create', [MasterCompanyController::class, 'create'])->name('master-company.create');
            Route::post('/create', [MasterCompanyController::class, 'store'])->name('master-company.store');
            Route::get('/edit/{id}', [MasterCompanyController::class, 'edit'])->name('master-company.edit');
            Route::put('/edit/{id}', [MasterCompanyController::class, 'update'])->name('master-company.update');
            Route::delete('/delete/{id}', [MasterCompanyController::class, 'destroy'])->name('master-company.destroy');
        });

        Route::prefix('department')->group(function () {
            Route::get('/', [MasterDepartment::class, 'index'])->name('master-department.index');
            Route::get('/create', [MasterDepartment::class, 'create'])->name('master-department.create');
            Route::post('/create', [MasterDepartment::class, 'store'])->name('master-department.store');
            Route::get('/edit/{id}', [MasterDepartment::class, 'edit'])->name('master-department.edit');
            Route::put('/edit/{id}', [MasterDepartment::class, 'update'])->name('master-department.update');
            Route::delete('/delete/{id}', [MasterDepartment::class, 'destroy'])->name('master-department.destroy');
        });
    });


    // Transaction routes
    Route::prefix('transaction')->group(function () {

        // Profile routes
        Route::prefix('hazards')->name('transaction-')->group(function () {
            Route::get('/', [HazardController::class, 'index'])->name('hazards.index');
            Route::get('/create', [HazardController::class, 'create'])->name('hazards.create');
            Route::post('/', [HazardController::class, 'store'])->name('hazards.store');
            Route::delete('/delete/{id}', [HazardController::class, 'destroy'])->name('hazards.destroy');
            Route::get('/show/{id}', [HazardController::class, 'show'])->name('hazards.show');
            Route::get('/export', [HazardController::class, 'export'])->name('hazards.export');
            Route::get('/admin_edit/{id}', [HazardController::class, 'admin_edit'])->name('hazards.admin_edit');
            Route::put('/admin_edit/{id}', [HazardController::class, 'admin_update'])->name('hazards.admin_update');
        });
        Route::prefix('incident-notification')->name('transaction-')->group(function () {
            Route::get('/', [IncidentNotificationController::class, 'index'])->name('incidentNotification.index');
            Route::get('/create', [IncidentNotificationController::class, 'create'])->name('incidentNotification.create');
            Route::post('/create', [IncidentNotificationController::class, 'store'])->name('incidentNotification.store');
            Route::get('/show/{id}', [IncidentNotificationController::class, 'show'])->name('incidentNotification.show');
            Route::get('/edit/{id}', [IncidentNotificationController::class, 'edit'])->name('incidentNotification.edit');
            Route::put('/edit/{id}', [IncidentNotificationController::class, 'update'])->name('incidentNotification.update');
            Route::delete('/delete/{id}', [IncidentNotificationController::class, 'destroy'])->name('incidentNotification.delete');
            Route::post('/approve/{id}', [IncidentNotificationController::class, 'approve'])->name('incidentNotification.approve');
            Route::get('/export', [IncidentNotificationController::class, 'export'])->name('incidentNotification.export');
        });
        Route::prefix('incident-investigation')->name('transaction-')->group(function () {
            Route::get('/', [IncidentInvestigationController::class, 'index'])->name('incidentInvestigation.index');
            Route::get('/create/{id}', [IncidentInvestigationController::class, 'create'])->name('incidentInvestigation.create');
            Route::post('/create/{id}', [IncidentInvestigationController::class, 'store'])->name('incidentInvestigation.store');
            Route::get('/edit/{id}', [IncidentInvestigationController::class, 'edit'])->name('incidentInvestigation.edit');
            Route::put('/edit/{id}', [IncidentInvestigationController::class, 'update'])->name('incidentInvestigation.update');
            Route::delete('/delete/{id}', [IncidentInvestigationController::class, 'destroy'])->name('incidentInvestigation.destroy');
            Route::get('/show/{id}', [IncidentInvestigationController::class, 'show'])->name('incidentInvestigation.show');
            Route::post('/approve/{id}', [IncidentInvestigationController::class, 'approve'])->name('incidentInvestigation.approve');
            Route::get('/export', [IncidentInvestigationController::class, 'export'])->name('incidentInvestigation.export');
        });
        Route::prefix('workplace-control')->name('transaction-')->group(function () {
            Route::delete('/delete/{id}', [WorkPlaceController::class, 'destroy'])->name('workPlace.destroy');
            Route::get('/', [WorkPlaceController::class, 'index'])->name('workPlace.index');
            Route::get('/create', [WorkPlaceController::class, 'create'])->name('workPlace.create');
            Route::post('/create', [WorkPlaceController::class, 'store'])->name('workPlace.store');
            Route::get('/show/{id}', [WorkPlaceController::class, 'show'])->name('workPlace.show');
            Route::get('/export', [WorkPlaceController::class, 'export'])->name('workPlace.export');
            Route::get('/admin_edit/{id}', [WorkPlaceController::class, 'admin_edit'])->name('workPlace.admin_edit');
            Route::put('/admin_edit/{id}', [WorkPlaceController::class, 'admin_update'])->name('workPlace.admin_update');
        });
        Route::prefix('corrective-actions')->name('transaction-')->group(function () {
            Route::get('/', [CorrectiveActionController::class, 'index'])->name('correctiveAction.index');
            Route::get('/create', [CorrectiveActionController::class, 'create'])->name('correctiveAction.create');
            Route::post('/create', [CorrectiveActionController::class, 'store'])->name('correctiveAction.store');
            Route::get('/edit/{id}', [CorrectiveActionController::class, 'edit'])->name('correctiveAction.edit');
            Route::put('/edit/{id}', [CorrectiveActionController::class, 'update'])->name('correctiveAction.update');
            Route::delete('/delete/{id}', [CorrectiveActionController::class, 'destroy'])->name('correctiveAction.delete');
            Route::get('/show/{id}', [CorrectiveActionController::class, 'show'])->name('correctiveAction.show');
            Route::put('/approve/{id}', [CorrectiveActionController::class, 'approve'])->name('correctiveAction.approve');
            Route::get('/export', [CorrectiveActionController::class, 'export'])->name('correctiveAction.export');
        });
        Route::prefix('license')->name('transaction-')->group(function () {
            Route::get('/', [LicenseController::class, 'index'])->name('license.index');
            Route::get('/create', [LicenseController::class, 'create'])->name('license.create');
            Route::post('/create', [LicenseController::class, 'store'])->name('license.store');
            Route::get('/edit/{id}', [LicenseController::class, 'edit'])->name('license.edit');
            Route::put('/edit/{id}', [LicenseController::class, 'update'])->name('license.update');
            Route::delete('/delete/{id}', [LicenseController::class, 'destroy'])->name('license.destroy');
            Route::put('/approve/{id}', [LicenseController::class, 'approve'])->name('license.approve');
            Route::get('/show/{id}', [LicenseController::class, 'show'])->name('license.show');
            Route::get('/view_pdf/{id}', [LicenseController::class, 'view_pdf'])->name('license.view_pdf');
            Route::get('/export', [LicenseController::class, 'export'])->name('license.export');
            Route::get('/admin_edit/{id}', [LicenseController::class, 'admin_edit'])->name('license.admin_edit');
            Route::put('/admin_edit/{id}', [LicenseController::class, 'admin_update'])->name('license.admin_update');
        });
        Route::prefix('personnel-assignments')->name('transaction-personnel-assignments.')->group(function () {
            Route::get('/', [PersonnelAssignmentController::class, 'index'])->name('index');
            Route::get('/create', [PersonnelAssignmentController::class, 'create'])->name('create');
            Route::post('/create_detail', [PersonnelAssignmentController::class, 'store_detail'])->name('store_detail');
            Route::delete('/delete_detail/{id}', [PersonnelAssignmentController::class, 'destroy_detail'])->name('destroy_detail');
            // Route::post('/create', [PersonnelAssignmentController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [PersonnelAssignmentController::class, 'edit'])->name('edit');
            Route::put('/edit/{id}', [PersonnelAssignmentController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [PersonnelAssignmentController::class, 'destroy'])->name('destroy');
            Route::get('/export_excel', [HazardController::class, 'export'])->name('export');
            Route::get('/get_employee_detail', [PersonnelAssignmentController::class, 'get_employee_detail'])->name('get_employee_detail');
            Route::get('/view_pdf/{id}', [PersonnelAssignmentController::class, 'view_pdf'])->name('view_pdf');
            Route::put('/approve/{id}', [PersonnelAssignmentController::class, 'approve'])->name('approve');
            Route::get('/show/{id}', [PersonnelAssignmentController::class, 'show'])->name('show');
        });
        Route::prefix('asset')->name('transaction-')->group(function () {
            Route::get('/', [AssetController::class, 'index'])->name('asset.index');
            Route::get('/create', [AssetController::class, 'create'])->name('asset.create');
            Route::post('/create', [AssetController::class, 'store'])->name('asset.store');
            Route::get('/show/{id}', [AssetController::class, 'show'])->name('asset.show');
            Route::get('/edit/{id}', [AssetController::class, 'edit'])->name('asset.edit');
            Route::put('/edit/{id}', [AssetController::class, 'update'])->name('asset.update');
            Route::delete('/delete/{id}', [AssetController::class, 'destroy'])->name('asset.destroy');
            Route::post('/approve/{id}', [AssetController::class, 'approve'])->name('asset.approve');
            Route::get('/export', [AssetController::class, 'export'])->name('asset.export');
            Route::get('/admin_edit/{id}', [AssetController::class, 'admin_edit'])->name('asset.admin_edit');
            Route::put('/admin_edit/{id}', [AssetController::class, 'admin_update'])->name('asset.admin_update');
            
            Route::post('/update_asset_status/{id}', [AssetController::class, 'update_asset_status'])->name('asset.update_asset_status');
        });
        Route::prefix('badge')->name('transaction-')->group(function () {
            Route::get('/', [BadgeController::class, 'index'])->name('badge.index');
            Route::get('/create', [BadgeController::class, 'create'])->name('badge.create');
            Route::post('/create', [BadgeController::class, 'store'])->name('badge.store');
            Route::get('/show/{id}', [BadgeController::class, 'show'])->name('badge.show');
            Route::get('/edit/{id}', [BadgeController::class, 'edit'])->name('badge.edit');
            Route::put('/edit/{id}', [BadgeController::class, 'update'])->name('badge.update');
            Route::delete('/delete/{id}', [BadgeController::class, 'destroy'])->name('badge.destroy');
            Route::post('/approve/{id}', [BadgeController::class, 'approve'])->name('badge.approve');
            Route::get('/export', [BadgeController::class, 'export'])->name('badge.export');
        });
        Route::prefix('monthly-reports')->name('transaction-monthly-reports.')->group(function () {
            Route::get('/', [MonthlyReportController::class, 'index'])->name('index');
            Route::get('/create', [MonthlyReportController::class, 'create'])->name('create');
            Route::post('/create', [MonthlyReportController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [MonthlyReportController::class, 'edit'])->name('edit');
            Route::put('/edit/{id}', [MonthlyReportController::class, 'update'])->name('update');
            Route::get('/view/{id}', [MonthlyReportController::class, 'show'])->name('show');
            Route::delete('/delete/{id}', [MonthlyReportController::class, 'destroy'])->name('destroy');
            Route::get('/view_pdf/{id}', [MonthlyReportController::class, 'view_pdf'])->name('view_pdf');
            Route::get('/view_pdf_contractor/{id}', [MonthlyReportController::class, 'view_pdf_contractor'])->name('view_pdf_contractor');
            Route::put('/approve/{id}', [MonthlyReportController::class, 'approve'])->name('approve');
            Route::get('/download/excel', [MonthlyReportController::class, 'excel'])->name('excel');
            Route::get('/get-company-data/{id}', function ($id) {
                $company = App\Models\Company::with('pjoUser')->find($id);
                return response()->json($company);
            })->name('get.company.data');
        });
        Route::prefix('delegation')->name('transaction-delegation.')->group(function () {
            Route::get('/', [DelegationController::class, 'index'])->name('index');
            Route::post('/store', [DelegationController::class, 'store'])->name('store');
        });
        
        Route::get('/view-storage/{folder}/{filename}', function ($folder, $filename) {
            $basePath = rtrim(env('FILE_PATH', '/data/msafe/'));
            $path = $basePath . $folder . '/' . $filename;
            if (!File::exists($path)) {
                echo "File not found: $path";
                exit;
                //abort(404);
            }
            $type = File::mimeType($path);
            return response()->file($path, [
                'Content-Type' => $type,
                'Content-Disposition' => 'inline; filename="' . $filename . '"'
            ]);
        })->name('storage.external')->where('filename', '^.*$');

        // Role Management
        Route::resource('roles', HazardController::class);

        // User Management
        Route::resource('users', UserController::class);

        // Role Permission Management
        Route::get('role-permissions', [RolePermissionController::class, 'index'])->name('role-permissions.index');
        Route::get('role-permissions/{role}', [RolePermissionController::class, 'show']);
        Route::post('role-permissions', [RolePermissionController::class, 'store'])->name('role-permissions.store');
    });
});
