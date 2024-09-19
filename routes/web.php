<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ChanelManagement\CategoryChanelcontroller;
use App\Http\Controllers\ChanelManagement\Chanelcontroller;
use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Company\OwnerController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Keuangan\DailyincomeController;
use App\Http\Controllers\Keuangan\SubcriptionController;
use App\Http\Controllers\Master\PacketController;
use App\Http\Controllers\Master\RegionController;
use App\Http\Controllers\Master\StbController;
use App\Http\Controllers\Settings\RoleController;
use App\Http\Controllers\Settings\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/auth');
Route::get('auth', [AuthController::class, 'index'])->middleware('guest')->name('auth');
Route::post('auth/signin', [AuthController::class, 'signin'])->name('auth.signin');
Route::get('auth/signout', [AuthController::class, 'signout'])->name('auth.signout');
//chanel management route

// /testing

Route::get('stream/{replaceurl}', [Chanelcontroller::class, 'stream'])->name('stream');

Route::prefix('admin')->middleware('auth')->group(function () {

    Route::get('', function () {
        return redirect()->route('dashboard');
    });
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('can:read-dashboard');



    //master
    Route::prefix('master')->group(function () {
        //chanel
        Route::prefix('stb')->group(function () {
            Route::get('/', [StbController::class, 'index'])->name('stb')->middleware('can:read-stb');
            Route::get('getData', [StbController::class, 'getData'])->name('stb.getdata');
            Route::get('/tambah', [StbController::class, 'create'])->name('stb.add')->middleware('can:read-stb');
            Route::post('store', [StbController::class, 'store'])->name('stb.store');
            Route::get('/edit/{id}', [StbController::class, 'show'])->name('stb.edit')->middleware('can:read-stb');
            Route::put('/update/{id}', [StbController::class, 'update'])->name('stb.update');
            Route::delete('/delete/{id}', [StbController::class, 'destroy'])->name('stb.delete')->middleware('can:read-stb');
        });

        //region route
        Route::prefix('region')->group(function () {
            Route::get('', [RegionController::class, 'index'])->name('region')->middleware('can:read-region');
            Route::get('getData', [RegionController::class, 'getData'])->name('region.getdata');
            Route::get('/tambah', [RegionController::class, 'create'])->name('region.add')->middleware('can:create-region');
            Route::post('store', [RegionController::class, 'store'])->name('region.store');
            Route::get('/edit/{id}', [RegionController::class, 'show'])->name('region.edit')->middleware('can:update-region');
            Route::put('/update/{id}', [RegionController::class, 'update'])->name('region.update');
            Route::delete('/delete/{id}', [RegionController::class, 'destroy'])->name('region.delete')->middleware('can:delete-region');
        });


        Route::prefix('paket')->group(function () {
            Route::get('', [PacketController::class, 'index'])->name('paket')->middleware('can:read-paket');
            Route::get('getData', [PacketController::class, 'getData'])->name('paket.getdata');
            Route::get('/tambah', [PacketController::class, 'create'])->name('paket.add')->middleware('can:create-paket');
            Route::post('store', [PacketController::class, 'store'])->name('paket.store');
            Route::get('/edit/{id}', [PacketController::class, 'show'])->name('paket.edit')->middleware('can:update-paket');
            Route::put('/update/{id}', [PacketController::class, 'update'])->name('paket.update');
            Route::delete('/delete/{id}', [PacketController::class, 'destroy'])->name('paket.delete')->middleware('can:delete-paket');
        });
    });


    //chanel management route
    Route::prefix('chanel-management')->group(function () {
        //chanel
        Route::prefix('chanel')->group(function () {
            Route::get('/', [Chanelcontroller::class, 'index'])->name('chanel')->middleware('can:read-chanel');
            Route::get('/player/{id}', [Chanelcontroller::class, 'vidiochanel'])->name('chanel.player')->middleware('can:read-chanel-player');
            Route::get('getData', [Chanelcontroller::class, 'getData'])->name('chanel.getdata');
            Route::get('/tambah', [Chanelcontroller::class, 'create'])->name('chanel.add')->middleware('can:create-chanel');
            Route::post('store', [Chanelcontroller::class, 'store'])->name('chanel.store');
            Route::get('/edit/{id}', [Chanelcontroller::class, 'show'])->name('chanel.edit')->middleware('can:update-chanel');
            Route::put('/update/{id}', [Chanelcontroller::class, 'update'])->name('chanel.update');
            Route::delete('/delete/{id}', [Chanelcontroller::class, 'destroy'])->name('chanel.delete')->middleware('can:delete-chanel');
        });

        //Categori route
        Route::prefix('categori')->group(function () {
            Route::get('', [CategoryChanelcontroller::class, 'index'])->name('categori-chanel')->middleware('can:read-categori');
            Route::get('getData', [CategoryChanelcontroller::class, 'getData'])->name('categori-chanel.getdata');
            Route::get('/tambah', [CategoryChanelcontroller::class, 'create'])->name('categori-chanel.add')->middleware('can:create-categori');
            Route::post('store', [CategoryChanelcontroller::class, 'store'])->name('categori-chanel.store');
            Route::get('/edit/{id}', [CategoryChanelcontroller::class, 'show'])->name('categori-chanel.edit')->middleware('can:update-categori');
            Route::put('/update/{id}', [CategoryChanelcontroller::class, 'update'])->name('categori-chanel.update');
            Route::delete('/delete/{id}', [CategoryChanelcontroller::class, 'destroy'])->name('categori-chanel.delete')->middleware('can:delete-categori');
        });
    });

    //customer route
    Route::prefix('customer')->group(
        function () {
            Route::get('', [CustomerController::class, 'index'])->name('customer')->middleware('can:read-customer');
            Route::get('detail/{id}', [CustomerController::class, 'detail'])->name('customer.detail')->middleware('can:read-customer');
            Route::get('getData', [CustomerController::class, 'getData'])->name('customer.getdata');
            Route::post('getcompany', [CustomerController::class, 'getcompany'])->name('customer.getcompany');
            Route::get('/tambah', [CustomerController::class, 'create'])->name('customer.add')->middleware('can:create-customer');
            Route::post('store', [CustomerController::class, 'store'])->name('customer.store');
            Route::get('/edit/{id}', [CustomerController::class, 'show'])->name('customer.edit')->middleware('can:update-customer');
            Route::put('/update/{id}', [CustomerController::class, 'update'])->name('customer.update');
            Route::delete('/delete/{id}', [CustomerController::class, 'destroy'])->name('customer.delete')->middleware('can:delete-customer');
        }
    );

    //route company
    Route::prefix('company')->group(function () {
        Route::prefix('owner')->group(
            function () {
                Route::get('', [OwnerController::class, 'index'])->name('owner')->middleware('can:read-owner');
                Route::get('getData', [OwnerController::class, 'getData'])->name('owner.getdata');
                Route::get('/tambah', [OwnerController::class, 'create'])->name('owner.add')->middleware('can:create-owner');
                Route::post('store', [OwnerController::class, 'store'])->name('owner.store');
                Route::get('/edit/{id}', [OwnerController::class, 'show'])->name('owner.edit')->middleware('can:update-owner');
                Route::put('/update/{id}', [OwnerController::class, 'update'])->name('owner.update');
                Route::delete('/delete/{id}', [OwnerController::class, 'destroy'])->name('owner.delete')->middleware('can:delete-owner');
            }
        );

        Route::get('', [CompanyController::class, 'index'])->name('company')->middleware('can:read-company');
        Route::get('getData', [CompanyController::class, 'getData'])->name('company.getdata');
        Route::get('/tambah', [CompanyController::class, 'create'])->name('company.add')->middleware('can:read-company');
        Route::post('store', [CompanyController::class, 'store'])->name('company.store');
        Route::get('/edit/{id}', [CompanyController::class, 'show'])->name('company.edit')->middleware('can:read-company');
        Route::put('/update/{id}', [CompanyController::class, 'update'])->name('company.update');
        Route::delete('/delete/{id}', [CompanyController::class, 'destroy'])->name('company.delete')->middleware('can:read-company');
    });


    Route::get('print-standart/{id}', [SubcriptionController::class, 'PrintStandart'])->name('print.standart');
    //route keuangan
    Route::prefix('keuangan')->group(function () {
        Route::prefix('subcription')->group(
            function () {
                Route::get('', [SubcriptionController::class, 'index'])->name('keuangan')->middleware('can:read-owner');
                Route::get('getData', [SubcriptionController::class, 'getData'])->name('keuangan.getdata');
                Route::get('/tambah', [SubcriptionController::class, 'create'])->name('keuangan.add')->middleware('can:create-owner');
                Route::post('store', [SubcriptionController::class, 'store'])->name('keuangan.store');
                Route::get('/edit/{id}', [SubcriptionController::class, 'show'])->name('keuangan.edit')->middleware('can:update-owner');
                Route::put('/update/{id}', [SubcriptionController::class, 'update'])->name('keuangan.update');
                Route::delete('/delete/{id}', [SubcriptionController::class, 'destroy'])->name('keuangan.delete')->middleware('can:delete-owner');
            }
        );
        Route::prefix('income-harian')->group(
            function () {
                Route::get('', [DailyincomeController::class, 'index'])->name('dailyincome')->middleware('can:read-owner');
                Route::get('getData', [DailyincomeController::class, 'getData'])->name('dailyincome.getdata');
            }
        );
    });

    //setting route
    Route::prefix('settings')->group(function () {
        Route::prefix('role')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('role')->middleware('can:read-role');
            Route::get('getData', [RoleController::class, 'getData'])->name('role.getdata');
            Route::get('/tambah', [RoleController::class, 'create'])->name('role.add')->middleware('can:create-role');
            Route::post('store', [RoleController::class, 'store'])->name('role.store');
            Route::get('/edit/{id}', [RoleController::class, 'show'])->name('role.edit')->middleware('can:update-role');
            Route::put('/update/{id}', [RoleController::class, 'update'])->name('role.update');
            Route::delete('/delete/{id}', [RoleController::class, 'destroy'])->name('role.delete')->middleware('can:delete-role');
        });
        Route::prefix('user')->group(function () {
            Route::get('', [UserController::class, 'index'])->name('user')->middleware('can:read-users');
            Route::get('getData', [UserController::class, 'getData'])->name('user.getdata');
            Route::get('/tambah', [UserController::class, 'create'])->name('user.add')->middleware('can:create-users');
            Route::post('store', [UserController::class, 'store'])->name('user.store');
            Route::get('/edit/{id}', [UserController::class, 'show'])->name('user.edit')->middleware('can:update-users');
            Route::put('/update/{id}', [UserController::class, 'update'])->name('user.update');
            Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('user.delete')->middleware('can:delete-users');
        });
    });
});




// // Dashboard
// Route::get('/dashboard-general-dashboard', function () {
//     return view('pages.dashboard-general-dashboard', ['type_menu' => 'dashboard']);
// });
// Route::get('/dashboard-ecommerce-dashboard', function () {
//     return view('pages.dashboard-ecommerce-dashboard', ['type_menu' => 'dashboard']);
// });


// // Layout
// Route::get('/layout-default-layout', function () {
//     return view('pages.layout-default-layout', ['type_menu' => 'layout']);
// });

// // Blank Page
// Route::get('/blank-page', function () {
//     return view('pages.blank-page', ['type_menu' => '']);
// });

// // Bootstrap
// Route::get('/bootstrap-alert', function () {
//     return view('pages.bootstrap-alert', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-badge', function () {
//     return view('pages.bootstrap-badge', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-breadcrumb', function () {
//     return view('pages.bootstrap-breadcrumb', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-buttons', function () {
//     return view('pages.bootstrap-buttons', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-card', function () {
//     return view('pages.bootstrap-card', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-carousel', function () {
//     return view('pages.bootstrap-carousel', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-collapse', function () {
//     return view('pages.bootstrap-collapse', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-dropdown', function () {
//     return view('pages.bootstrap-dropdown', ['type_menu' => 'bootstrap']);
// });
Route::get('/bootstrap-form', function () {
    return view('pages.bootstrap-form', ['type_menu' => 'bootstrap']);
});
// Route::get('/bootstrap-list-group', function () {
//     return view('pages.bootstrap-list-group', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-media-object', function () {
//     return view('pages.bootstrap-media-object', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-modal', function () {
//     return view('pages.bootstrap-modal', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-nav', function () {
//     return view('pages.bootstrap-nav', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-navbar', function () {
//     return view('pages.bootstrap-navbar', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-pagination', function () {
//     return view('pages.bootstrap-pagination', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-popover', function () {
//     return view('pages.bootstrap-popover', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-progress', function () {
//     return view('pages.bootstrap-progress', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-table', function () {
//     return view('pages.bootstrap-table', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-tooltip', function () {
//     return view('pages.bootstrap-tooltip', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-typography', function () {
//     return view('pages.bootstrap-typography', ['type_menu' => 'bootstrap']);
// });


// // components
// Route::get('/components-article', function () {
//     return view('pages.components-article', ['type_menu' => 'components']);
// });
// Route::get('/components-avatar', function () {
//     return view('pages.components-avatar', ['type_menu' => 'components']);
// });
// Route::get('/components-chat-box', function () {
//     return view('pages.components-chat-box', ['type_menu' => 'components']);
// });
// Route::get('/components-empty-state', function () {
//     return view('pages.components-empty-state', ['type_menu' => 'components']);
// });
// Route::get('/components-gallery', function () {
//     return view('pages.components-gallery', ['type_menu' => 'components']);
// });
// Route::get('/components-hero', function () {
//     return view('pages.components-hero', ['type_menu' => 'components']);
// });
// Route::get('/components-multiple-upload', function () {
//     return view('pages.components-multiple-upload', ['type_menu' => 'components']);
// });
// Route::get('/components-pricing', function () {
//     return view('pages.components-pricing', ['type_menu' => 'components']);
// });
// Route::get('/components-statistic', function () {
//     return view('pages.components-statistic', ['type_menu' => 'components']);
// });
// Route::get('/components-tab', function () {
//     return view('pages.components-tab', ['type_menu' => 'components']);
// });
// Route::get('/components-table', function () {
//     return view('pages.components-table', ['type_menu' => 'components']);
// });
// Route::get('/components-user', function () {
//     return view('pages.components-user', ['type_menu' => 'components']);
// });
// Route::get('/components-wizard', function () {
//     return view('pages.components-wizard', ['type_menu' => 'components']);
// });

// // forms
// Route::get('/forms-advanced-form', function () {
//     return view('pages.forms-advanced-form', ['type_menu' => 'forms']);
// });
// Route::get('/forms-editor', function () {
//     return view('pages.forms-editor', ['type_menu' => 'forms']);
// });
// Route::get('/forms-validation', function () {
//     return view('pages.forms-validation', ['type_menu' => 'forms']);
// });

// // google maps
// // belum tersedia

// // modules
// Route::get('/modules-calendar', function () {
//     return view('pages.modules-calendar', ['type_menu' => 'modules']);
// });
// Route::get('/modules-chartjs', function () {
//     return view('pages.modules-chartjs', ['type_menu' => 'modules']);
// });
// Route::get('/modules-datatables', function () {
//     return view('pages.modules-datatables', ['type_menu' => 'modules']);
// });
// Route::get('/modules-flag', function () {
//     return view('pages.modules-flag', ['type_menu' => 'modules']);
// });
// Route::get('/modules-font-awesome', function () {
//     return view('pages.modules-font-awesome', ['type_menu' => 'modules']);
// });
// Route::get('/modules-ion-icons', function () {
//     return view('pages.modules-ion-icons', ['type_menu' => 'modules']);
// });
// Route::get('/modules-owl-carousel', function () {
//     return view('pages.modules-owl-carousel', ['type_menu' => 'modules']);
// });
// Route::get('/modules-sparkline', function () {
//     return view('pages.modules-sparkline', ['type_menu' => 'modules']);
// });
// Route::get('/modules-sweet-alert', function () {
//     return view('pages.modules-sweet-alert', ['type_menu' => 'modules']);
// });
// Route::get('/modules-toastr', function () {
//     return view('pages.modules-toastr', ['type_menu' => 'modules']);
// });
// Route::get('/modules-vector-map', function () {
//     return view('pages.modules-vector-map', ['type_menu' => 'modules']);
// });
// Route::get('/modules-weather-icon', function () {
//     return view('pages.modules-weather-icon', ['type_menu' => 'modules']);
// });

// // auth
// Route::get('/auth-forgot-password', function () {
//     return view('pages.auth-forgot-password', ['type_menu' => 'auth']);
// });
// Route::get('/auth-login', function () {
//     return view('pages.auth-login', ['type_menu' => 'auth']);
// });
// Route::get('/auth-login2', function () {
//     return view('pages.auth-login2', ['type_menu' => 'auth']);
// });
// Route::get('/auth-register', function () {
//     return view('pages.auth-register', ['type_menu' => 'auth']);
// });
// Route::get('/auth-reset-password', function () {
//     return view('pages.auth-reset-password', ['type_menu' => 'auth']);
// });

// // error
// Route::get('/error-403', function () {
//     return view('pages.error-403', ['type_menu' => 'error']);
// });
// Route::get('/error-404', function () {
//     return view('pages.error-404', ['type_menu' => 'error']);
// });
// Route::get('/error-500', function () {
//     return view('pages.error-500', ['type_menu' => 'error']);
// });
// Route::get('/error-503', function () {
//     return view('pages.error-503', ['type_menu' => 'error']);
// });

// // features
// Route::get('/features-activities', function () {
//     return view('pages.features-activities', ['type_menu' => 'features']);
// });
// Route::get('/features-post-create', function () {
//     return view('pages.features-post-create', ['type_menu' => 'features']);
// });
// Route::get('/features-post', function () {
//     return view('pages.features-post', ['type_menu' => 'features']);
// });
// Route::get('/features-profile', function () {
//     return view('pages.features-profile', ['type_menu' => 'features']);
// });
// Route::get('/features-settings', function () {
//     return view('pages.features-settings', ['type_menu' => 'features']);
// });
// Route::get('/features-setting-detail', function () {
//     return view('pages.features-setting-detail', ['type_menu' => 'features']);
// });
// Route::get('/features-tickets', function () {
//     return view('pages.features-tickets', ['type_menu' => 'features']);
// });

// // utilities
// Route::get('/utilities-contact', function () {
//     return view('pages.utilities-contact', ['type_menu' => 'utilities']);
// });
// Route::get('/utilities-invoice', function () {
//     return view('pages.utilities-invoice', ['type_menu' => 'utilities']);
// });
// Route::get('/utilities-subscribe', function () {
//     return view('pages.utilities-subscribe', ['type_menu' => 'utilities']);
// });

// // credits
// Route::get('/credits', function () {
//     return view('pages.credits', ['type_menu' => '']);
// });
