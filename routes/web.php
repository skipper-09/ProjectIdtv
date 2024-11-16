<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ChanelManagement\CategoryChanelcontroller;
use App\Http\Controllers\ChanelManagement\Chanelcontroller;
use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Company\OwnerController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Keuangan\DailyincomeController;
use App\Http\Controllers\Keuangan\PeriodeIncomeController;

use App\Http\Controllers\Keuangan\FeeClaimController;
use App\Http\Controllers\Keuangan\StatistikController;
use App\Http\Controllers\Keuangan\SubcriptionController;
use App\Http\Controllers\Keuangan\TagihanController;
use App\Http\Controllers\Master\BankController;
use App\Http\Controllers\Master\PacketController;
use App\Http\Controllers\Master\RegionController;
use App\Http\Controllers\Master\StbController;
use App\Http\Controllers\Monitor\monitoringcustomercontroller;
use App\Http\Controllers\Movie\EpisodeController;
use App\Http\Controllers\Movie\GenreController;
use App\Http\Controllers\Movie\MovieController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Reseller\PendapatanController;
use App\Http\Controllers\Reseller\ResellerController;
use App\Http\Controllers\Reseller\ResellerPaketController;
use App\Http\Controllers\Settings\LogController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\RoleController;
use App\Http\Controllers\Settings\UserController;
use App\Http\Controllers\Settings\VersionController;
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

Route::get('register/pelanggan', [CustomerController::class, 'CustomerRegister'])->middleware('guest')->name('customer.register');
Route::post('post/pelangganpost', [CustomerController::class, 'customerpost'])->name('customer.post');

Route::redirect('/', '/auth');
Route::get('auth', [AuthController::class, 'index'])->middleware('guest')->name('auth');
Route::post('auth/signin', [AuthController::class, 'signin'])->name('auth.signin');
Route::get('auth/signout', [AuthController::class, 'signout'])->name('auth.signout');

// /testing
Route::get('stream/{replaceurl}', [Chanelcontroller::class, 'stream'])->name('stream');


//hadler 404 page
Route::fallback(function () {
    return response()->view('pages.error-404', [], 404);
});


//handle redirect paymet gateway midtrans
Route::get('payment/finish', [PaymentController::class, 'FinishPayment'])->name('finishpayment');


//chanel management route
Route::prefix('admin')->middleware('auth')->group(function () {

    Route::get('', function () {
        return redirect()->route('dashboard');
    });
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('can:read-dashboard');

    Route::get('customer-chart', [DashboardController::class, 'getChartData'])->name('customer.chart')->middleware('can:read-dashboard');



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
        Route::prefix('bank')->group(function () {
            Route::get('', [BankController::class, 'index'])->name('bank')->middleware('can:read-bank');
            Route::get('getData', [BankController::class, 'getData'])->name('bank.getdata');
            Route::get('/tambah', [BankController::class, 'create'])->name('bank.add')->middleware('can:create-bank');
            Route::post('store', [BankController::class, 'store'])->name('bank.store');
            Route::get('/edit/{id}', [BankController::class, 'show'])->name('bank.edit')->middleware('can:update-bank');
            Route::put('/update/{id}', [BankController::class, 'update'])->name('bank.update');
            Route::delete('/delete/{id}', [BankController::class, 'destroy'])->name('bank.delete')->middleware('can:delete-bank');
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
            Route::get('/export', [Chanelcontroller::class, 'export'])->name('chanel.export');
            Route::post('/importfile', [Chanelcontroller::class, 'ImportChanel'])->name('chanel.importfile');
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



    Route::prefix('reseller-management')->group(function () {
        //reseller
        Route::prefix('reseller')->group(function () {
            Route::get('/', [ResellerController::class, 'index'])->name('resellerdata')->middleware('can:read-reseller');
            Route::get('getData', [ResellerController::class, 'getData'])->name('resellerdata.getdata');
            Route::get('/tambah', [ResellerController::class, 'create'])->name('resellerdata.add')->middleware('can:create-reseller');
            Route::post('store', [ResellerController::class, 'store'])->name('resellerdata.store');
            Route::get('/edit/{id}', [ResellerController::class, 'show'])->name('resellerdata.edit')->middleware('can:update-reseller');
            Route::put('/update/{id}', [ResellerController::class, 'update'])->name('resellerdata.update');
            Route::delete('/delete/{id}', [ResellerController::class, 'destroy'])->name('resellerdata.delete')->middleware('can:delete-reseller');
        });

        // //paket reseller
        Route::prefix('paket-reseller')->group(function () {
            Route::get('', [ResellerPaketController::class, 'index'])->name('resellerdata-paket')->middleware('can:read-resellerpaket');
            Route::get('getData', [ResellerPaketController::class, 'getData'])->name('resellerdata-paket.getdata');
            Route::get('/tambah', [ResellerPaketController::class, 'create'])->name('resellerdata-paket.add')->middleware('can:create-resellerpaket');
            Route::post('store', [ResellerPaketController::class, 'store'])->name('resellerdata-paket.store');
            Route::get('/edit/{id}', [ResellerPaketController::class, 'show'])->name('resellerdata-paket.edit')->middleware('can:update-resellerpaket');
            Route::put('/update/{id}', [ResellerPaketController::class, 'update'])->name('resellerdata-paket.update');
            Route::delete('/delete/{id}', [ResellerPaketController::class, 'destroy'])->name('resellerdata-paket.delete')->middleware('can:delete-resellerpaket');
        });
    });




    //movie management route
    Route::prefix('movie')->group(function () {
        //movie
        Route::prefix('')->group(function () {
            Route::get('/', [MovieController::class, 'index'])->name('movie')->middleware('can:read-movie');
            Route::get('getData', [MovieController::class, 'getData'])->name('movie.getdata');
            Route::get('/player/{id}', [MovieController::class, 'Player'])->name('movie.player')->middleware('can:read-movie-player');
            Route::get('/tambah', [MovieController::class, 'create'])->name('movie.add')->middleware('can:create-movie');
            Route::post('store', [MovieController::class, 'store'])->name('movie.store');
            Route::get('/edit/{id}', [MovieController::class, 'show'])->name('movie.edit')->middleware('can:update-movie');
            Route::put('/update/{id}', [MovieController::class, 'update'])->name('movie.update');
            Route::delete('/delete/{id}', [MovieController::class, 'destroy'])->name('movie.delete')->middleware('can:delete-movie');
            Route::get('/export', [MovieController::class, 'export'])->name('movie.export');
            Route::post('/importfile', [MovieController::class, 'ImportMovie'])->name('movie.importfile');
        });

        //genre route
        Route::prefix('genre')->group(function () {
            Route::get('', [GenreController::class, 'index'])->name('genre')->middleware('can:read-genre');
            Route::get('getData', [GenreController::class, 'getData'])->name('genre.getdata');
            Route::get('/tambah', [GenreController::class, 'create'])->name('genre.add')->middleware('can:create-genre');
            Route::post('store', [GenreController::class, 'store'])->name('genre.store');
            Route::get('/edit/{id}', [GenreController::class, 'show'])->name('genre.edit')->middleware('can:update-genre');
            Route::put('/update/{id}', [GenreController::class, 'update'])->name('genre.update');
            Route::delete('/delete/{id}', [GenreController::class, 'destroy'])->name('genre.delete')->middleware('can:delete-genre');
        });

        Route::prefix('/{movie_id}/episode')->group(function () {
            Route::get('/', [EpisodeController::class, 'index'])->name('episode')->middleware('can:read-episode');
            Route::get('getData', [EpisodeController::class, 'getData'])->name('episode.getdata');
            Route::get('/player/{id}', [EpisodeController::class, 'Player'])->name('episode.player')->middleware('can:read-episode-player');
            Route::get('/tambah', [EpisodeController::class, 'create'])->name('episode.add')->middleware('can:create-episode');
            Route::post('store', [EpisodeController::class, 'store'])->name('episode.store');
            Route::get('/edit/{id}', [EpisodeController::class, 'show'])->name('episode.edit')->middleware('can:update-episode');
            Route::put('/update/{id}', [EpisodeController::class, 'update'])->name('episode.update');
            Route::delete('/delete/{id}', [EpisodeController::class, 'destroy'])->name('episode.delete')->middleware('can:delete-episode');
        });
    });


    //reseler
    //customer route

    Route::prefix('pendapatan')->group(
        function () {
            Route::get('', [PendapatanController::class, 'index'])->name('pendapatan.reseller')->middleware('can:read-customer');
            Route::get('/getData', [PendapatanController::class, 'getData'])->name('reseller.getdata');
            Route::get('/req-claim', [PendapatanController::class, 'reqClaim'])->name('reseller.reqclaim');
            Route::post('/req-claim/add', [PendapatanController::class, 'storeClaim'])->name('reseller.reqclaimstore');
            Route::get('/history-claim', [PendapatanController::class, 'HistoryClaim'])->name('reseller.historyclaim');
            Route::get('/getHistory', [PendapatanController::class, 'GetHistory'])->name('reseller.datahistory');
            Route::get('/detail/{id}', [PendapatanController::class, 'detail'])->name('reseller.detail');
        }
    );


    //customer route
    Route::prefix('customer')->group(
        function () {
            Route::get('', [CustomerController::class, 'index'])->name('customer')->middleware('can:read-customer');
            Route::get('detail/{id}', [CustomerController::class, 'detail'])->name('customer.detail')->middleware('can:read-customer');
            Route::get('getData', [CustomerController::class, 'getData'])->name('customer.getdata');
            Route::get('/get-paket-reseller', [CustomerController::class, 'getPaketReseller'])->name('customer.getPaketReseller');

            // Route::get('getpaket/{company_id}', [CustomerController::class, 'getPaket'])->name('customer.getpaket');
            // Route::post('getcompany', [CustomerController::class, 'getcompany'])->name('customer.getcompany');
            Route::get('/tambah', [CustomerController::class, 'create'])->name('customer.add')->middleware('can:create-customer');
            Route::post('store', [CustomerController::class, 'store'])->name('customer.store');
            Route::get('/edit/{id}', [CustomerController::class, 'show'])->name('customer.edit')->middleware('can:update-customer');
            Route::get('/renewsubscription/{id}', [CustomerController::class, 'RenewSubscription'])->name('customer.renew')->middleware('can:renew-customer');
            Route::post('/renewsubscription/add/{id}', [CustomerController::class, 'RenewSubscriptionAdd'])->name('customer.renewadd');
            Route::put('/update/{id}', [CustomerController::class, 'update'])->name('customer.update');
            Route::delete('/delete/{id}', [CustomerController::class, 'destroy'])->name('customer.delete')->middleware('can:delete-customer');
            Route::put('/reset/{id}', [CustomerController::class, 'resetDevice'])->name('customer.reset')->middleware('can:reset-device');
        }
    );

    //monitoring route
    Route::prefix('curentstream')->group(
        function () {
            Route::get('', [monitoringcustomercontroller::class, 'index'])->name('curentstream')->middleware('can:read-curentstream');
            Route::get('getData', [monitoringcustomercontroller::class, 'getData'])->name('curentstream.getdata');
        }
    );

    //route company
    Route::prefix('company')->group(function () {
        // Route::prefix('owner')->group(
        //     function () {
        //         Route::get('', [OwnerController::class, 'index'])->name('owner')->middleware('can:read-owner');
        //         Route::get('getData', [OwnerController::class, 'getData'])->name('owner.getdata');
        //         Route::get('/tambah', [OwnerController::class, 'create'])->name('owner.add')->middleware('can:create-owner');
        //         Route::post('store', [OwnerController::class, 'store'])->name('owner.store');
        //         Route::get('/edit/{id}', [OwnerController::class, 'show'])->name('owner.edit')->middleware('can:update-owner');
        //         Route::put('/update/{id}', [OwnerController::class, 'update'])->name('owner.update');
        //         Route::delete('/delete/{id}', [OwnerController::class, 'destroy'])->name('owner.delete')->middleware('can:delete-owner');
        //     }
        // );

        Route::get('', [CompanyController::class, 'index'])->name('company')->middleware('can:read-company');
        Route::get('getData', [CompanyController::class, 'getData'])->name('company.getdata');
        Route::get('/tambah', [CompanyController::class, 'create'])->name('company.add')->middleware('can:read-company');
        Route::post('store', [CompanyController::class, 'store'])->name('company.store');
        Route::get('/edit/{id}', [CompanyController::class, 'show'])->name('company.edit')->middleware('can:read-company');
        Route::put('/update/{id}', [CompanyController::class, 'update'])->name('company.update');
        Route::delete('/delete/{id}', [CompanyController::class, 'destroy'])->name('company.delete')->middleware('can:read-company');
    });


    Route::get('print-standart/{id}/{type}', [SubcriptionController::class, 'PrintStandart'])->name('print.standart');
    Route::get('print-thermal/{id}/{type}', [SubcriptionController::class, 'PrintThermal'])->name('print.thermal');
   
    //route keuangan
    Route::prefix('keuangan')->group(function () {
        Route::prefix('subcription')->group(
            function () {
                Route::get('', [SubcriptionController::class, 'index'])->name('keuangan')->middleware('can:read-subscription');
                Route::get('getData', [SubcriptionController::class, 'getData'])->name('keuangan.getdata');
                Route::get('/tambah', [SubcriptionController::class, 'create'])->name('keuangan.add')->middleware('can:create-subscription');
                Route::post('store', [SubcriptionController::class, 'store'])->name('keuangan.store');
                Route::get('/edit/{id}', [SubcriptionController::class, 'show'])->name('keuangan.edit')->middleware('can:update-subscription');
                Route::put('/update/{id}', [SubcriptionController::class, 'update'])->name('keuangan.update');
                Route::delete('/delete/{id}', [SubcriptionController::class, 'destroy'])->name('keuangan.delete')->middleware('can:delete-subscription');
            }
        );
        Route::prefix('income-harian')->group(
            function () {
                Route::get('', [DailyincomeController::class, 'index'])->name('dailyincome')->middleware('can:read-income-harian');
                Route::get('getData', [DailyincomeController::class, 'getData'])->name('dailyincome.getdata');
                Route::delete('/delete/{id}', [DailyincomeController::class, 'destroy'])->name('dailyincome.delete')->middleware('can:delete-income-harian');
            }
        );
        Route::prefix('income-periode')->group(
            function () {
                Route::post('', [PeriodeIncomeController::class, 'index'])->name('periodeincome')->middleware('can:read-income-periode');
                Route::get('getData', [PeriodeIncomeController::class, 'getData'])->name('periodeincome.getdata');
                Route::get('export-periode/{start}/{end}/{type}',[PeriodeincomeController::class,'ExportData'])->name('export-income-periode');
            }
        );
        Route::prefix('statistik')->group(
            function () {
                Route::get('', [StatistikController::class, 'index'])->name('statistik')->middleware('can:read-statistik');
                Route::get('getData', [PeriodeIncomeController::class, 'getData'])->name('periodeincome.getdata');
            }
        );
        Route::prefix('tagihan')->group(
            function () {
                Route::get('', [TagihanController::class, 'index'])->name('tagihan')->middleware('can:read-tagihan');
                Route::get('getData', [TagihanController::class, 'getData'])->name('tagihan.getdata');
            }
        );
        Route::prefix('fee-claim')->group(
            function () {
                Route::get('', [FeeClaimController::class, 'index'])->name('feeclaim')->middleware('can:read-feeclaim');
                Route::get('getData', [FeeClaimController::class, 'getData'])->name('feeclaim.getdata');
                Route::get('getDataDetailClaim', [FeeClaimController::class, 'getDataDetailClaim'])->name('feeclaim.getdatadetail');
                Route::get('show/{id}', [FeeClaimController::class, 'show'])->name('feeclaim.show')->middleware('can:proses-feeclaim');
                Route::put('/update/{id}', [FeeClaimController::class, 'Aprove'])->name('feeclaim.aprove');
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
        Route::prefix('log-activity')->group(function () {
            Route::get('', [LogController::class, 'index'])->name('log')->middleware('can:read-log');
            Route::get('getData', [LogController::class, 'getData'])->name('log.getdata');
            Route::delete('clear-log', [LogController::class, 'cleanlog'])->name('log.clear')->middleware('can:clean-log');
        });
        Route::prefix('version-control')->group(function () {
            Route::get('', [VersionController::class, 'index'])->name('versioncontrol')->middleware('can:read-version_control');
            Route::get('getData', [VersionController::class, 'getData'])->name('versioncontrol.getdata');
            Route::get('/tambah', [VersionController::class, 'create'])->name('versioncontrol.add')->middleware('can:create-version_control');
            Route::post('store', [VersionController::class, 'store'])->name('versioncontrol.store');
            Route::get('/edit/{id}', [VersionController::class, 'show'])->name('versioncontrol.edit')->middleware('can:update-version_control');
            Route::put('/update/{id}', [VersionController::class, 'update'])->name('versioncontrol.update');
            Route::delete('/delete/{id}', [VersionController::class, 'destroy'])->name('versioncontrol.delete')->middleware('can:delete-version_control');
        });
        //profile update
        Route::prefix('profile')->group(function () {
            Route::get('/edit/{id}', [ProfileController::class, 'index'])->name('profile.edit');
            Route::put('/update/{id}', [ProfileController::class, 'update'])->name('profile.update');
        });
    });
});
