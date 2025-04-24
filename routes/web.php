<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\InputKodePerusahaanController;
use App\Http\Controllers\MenampilkanPerusahaanController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\Masterdata\CoaController;
use App\Http\Controllers\Masterdata\CoaKelompokController;
use App\Http\Controllers\Masterdata\RolesController;
use App\Http\Controllers\Masterdata\PelangganController;
use App\Http\Controllers\Masterdata\SupplierController;
use App\Http\Controllers\Masterdata\KaryawanController;
use App\Http\Controllers\Masterdata\JabatanController;
use App\Http\Controllers\Masterdata\UsersController;
use App\Http\Controllers\Masterdata\JasaController;
use App\Http\Controllers\Transaksi\PembelianController;
use App\Http\Controllers\Transaksi\PembeliandetailController;
use App\Http\Controllers\Masterdata\AssetController;
use App\Http\Controllers\Masterdata\Kategori_barangController;
use App\Http\Controllers\Masterdata\ProdukController;
use App\Http\Controllers\Masterdata\Barang1Controller;
use App\Http\Controllers\Laporan\JurnalUmumController;
use App\Http\Controllers\Masterdata\DiscountController;
use App\Http\Controllers\Transaksi\PenjualanController;
use App\Http\Controllers\Transaksi\PresensiController;
use App\Http\Controllers\Transaksi\PenggajianController;
use App\Http\Controllers\Transaksi\BebanController;
use App\Http\Controllers\Laporan\LaporanLabaRugiController;
use App\Http\Controllers\Laporan\LaporanNeracaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Laporan\LaporanPerubahanModalController;
use App\Http\Controllers\Laporan\LaporanCashFlowController;
use Illuminate\Support\Facades\Auth;

// Auth routes
Auth::routes();

// Home route
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Dashboard route
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::get('/api/sales-data', [DashboardController::class, 'getSalesData'])->name('api.sales-data');
// Registration routes
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// Perusahaan routes
Route::middleware('auth')->group(function () {
    Route::get('/registrasiperusahaan', [PerusahaanController::class, 'showCreateForm'])
        ->name('registrasi-perusahaan');
    Route::post('/registrasiperusahaan', [PerusahaanController::class, 'createPerusahaan'])
        ->name('create.perusahaan');

    Route::get('/input-kode-perusahaan', [InputKodePerusahaanController::class, 'showInputForm'])
        ->middleware(['check.perusahaan'])
        ->name('input-kode-perusahaan');
    Route::post('/input-kode-perusahaan', [InputKodePerusahaanController::class, 'handleInputKode'])
        ->middleware(['check.perusahaan'])
        ->name('handle-input-kode');

    Route::get('/perusahaan', [MenampilkanPerusahaanController::class, 'index'])
        ->name('perusahaan.index')
        ->middleware('role:owner');
});

// Masterdata routes
Route::middleware('auth')->group(function () {
    // COA routes
    Route::resource('coa', CoaController::class)->except(['show'])->middleware('role:owner');
    Route::post('/coa/store', [CoaController::class, 'store'])->name('coa.store')->middleware('role:owner');

    // CoaKelompok routes
    Route::resource('coa-kelompok', CoaKelompokController::class)->middleware('role:owner');

    // Roles routes
    Route::resource('user_role', RolesController::class);

    // Barang routes
    // Route::resource('barang', BarangController::class);

    // Pelanggan routes
    Route::resource('pelanggan', PelangganController::class);

    // Supplier routes
    Route::resource('supplier', SupplierController::class);

    // Karyawan routes
    Route::resource('pegawai', KaryawanController::class)->middleware('role:owner');;

    // Jabatan routes
    Route::resource('jabatan', JabatanController::class)->middleware('role:owner');;

    // Users routes
    Route::resource('users', UsersController::class);

    // Jasa routes
    Route::resource('jasa', JasaController::class);

    // Asset routes
    Route::get('masterdata/aset/{aset}/depreciation', [AssetController::class, 'calculateDepreciation'])->name('aset.depreciation');
    Route::resource('aset', AssetController::class);
    Route::get('/api/depreciation-monthly/{asset}/{year}', [AssetController::class, 'getMonthlyDepreciation'])->name('asset.monthly-depreciation');

    // Katetgori Barang Routes
    Route::resource('kategori-produk', Kategori_barangController::class);

    // Produk routes
    Route::resource('produk', ProdukController::class);
    Route::get('/kartu-stok/{id_produk}', [ProdukController::class, 'getProductLog'])->name('produk.log');

    // Barang1 routes
    Route::resource('barang', Barang1Controller::class);

    // Discount routes
    Route::resource('diskon', DiscountController::class);
});

// Transaksi routes
// Pembelian routes
Route::middleware('auth')->group(function () {
    Route::resource('/pembelian', PembelianController::class);
    Route::get('pembelian/{id_pembelian}/detail', [PembelianController::class, 'show'])->name('pembelian.detail');
    Route::post('pembelian/{id}/pelunasan', [PembelianController::class, 'pelunasan'])->name('pembeliandetail.pelunasan');
    Route::post('/pembelian/{id}/pelunasan-auto', [PembelianController::class, 'pelunasan'])->name('pembelian.pelunasan.auto');
    Route::get('rekap_hutang/detail/{id_pembelian}', [PembelianController::class, 'getPembelianDetail'])->name('rekap.detail');
    Route::get('/pembelian/export/pdf', [PembelianController::class, 'exportPDF'])->name('pembelian.export.pdf');
    Route::get('/pembelian/export/excel', [PembelianController::class, 'exportExcel'])->name('pembelian.export.excel');


    // CRUD Routes for Pembelian Details (individual items within a transaction)
    Route::get('pembelian/{id}/details', [PembeliandetailController::class, 'index'])->name('pembeliandetail.index');
    Route::post('pembelian/{id}/details/store', [PembeliandetailController::class, 'store'])->name('pembeliandetail.store');
    Route::put('pembelian/detail/{id}', [PembeliandetailController::class, 'update'])->name('pembeliandetail.update');
    Route::delete('pembelian/detail/{id}', [PembeliandetailController::class, 'destroy'])->name('pembeliandetail.destroy');
    Route::get('/get-products-by-supplier/{supplierId}', [PembelianController::class, 'getProductsBySupplier'])->name('get-products-by-supplier');

    Route::get('/rekap-hutang', [PembelianController::class, 'rekapHutang'])->name('rekap_hutang');
    Route::post('/rekap-hutang/update/{id_pembelian}', [PembelianController::class, 'updateDueDate'])->name('rekap_hutang.update');
    Route::put('/rekap_hutang/update', [PembelianController::class, 'updateBulk'])->name('rekap_hutang.update_bulk');
    Route::put('/rekap-hutang/update-tenggat', [PembelianController::class, 'updateTenggat'])->name('rekap_hutang.update_tenggat');


    // Penjualan routes
    Route::resource('penjualan', PenjualanController::class);
    Route::get('penjualan/{id_penjualan}/selesaikan', [PenjualanController::class, 'edit'])->name('penjualan.selesaikan');
    Route::put('penjualan/{id_penjualan}/selesaikan', [PenjualanController::class, 'updateSelesai'])->name('penjualan.updateSelesai');

    // Penggajian routes
    Route::resource('penggajian', PenggajianController::class)->middleware('role:owner');
    Route::get('/penggajian/get-tarif/{id}', [PenggajianController::class, 'getTarifByKaryawan'])->name('penggajian.get-tarif')->middleware('role:owner');
    Route::get('/penggajian/get-total-service/{id}', [PenggajianController::class, 'getTotalServiceByKaryawan'])->middleware('role:owner');
    Route::get('/penggajian/{id}', [PenggajianController::class, 'show'])->name('penggajian.show')->middleware('role:owner');
    Route::get('/penggajian/get-total-kehadiran/{id}', [PenggajianController::class, 'getTotalKehadiranByKaryawan'])->middleware('role:owner');

    Route::resource('beban', BebanController::class);
    Route::get('/coa-by-date', [BebanController::class, 'getByDate']);});


// Laporan routes
Route::middleware('auth')->group(function () {
    Route::get('/jurnal-umum', [JurnalUmumController::class, 'index'])->name('jurnal-umum.index');
    Route::get('/buku-besar', [JurnalUmumController::class, 'bukuBesar'])->name('buku-besar');
    Route::get('/neraca-saldo', [JurnalUmumController::class, 'neracasaldo'])->name('neraca-saldo');
    Route::post('/neraca-saldo/create-coa', [JurnalUmumController::class, 'createCoaFromNeracaSaldo'])->name('create-coa-from-neraca-saldo');
    Route::get('/laba-rugi', [LaporanLabaRugiController::class, 'index'])->name('laba-rugi.index');
    Route::get('/neraca', [LaporanNeracaController::class, 'index'])->name('neraca.index');
    Route::get('/perubahan-modal', [LaporanPerubahanModalController::class, 'index'])->name('perubahan-modal.index');
    Route::get('/cashflow', [LaporanCashFlowController::class, 'cashFlow'])->name('cashflow');
    Route::get('/kartu-stok', [ProdukController::class, 'kartustok'])->name('produk.kartustok');
});

Route::get('/get-user-email/{id}', function ($id) {
    $user = App\Models\User::find($id);
    return response()->json(['email' => $user ? $user->email : null]);
});

Route::middleware('role:owner')->group(function () {
    Route::get('presensi/create', [PresensiController::class, 'create'])->name('presensi.create');
    Route::post('presensi/store', [PresensiController::class, 'store'])->name('presensi.store');
    Route::get('presensi/index', [PresensiController::class, 'index'])->name('presensi.index');
    Route::get('presensi/show/{date}', [PresensiController::class, 'show'])->name('presensi.show');
    Route::get('presensi/edit/{date}', [PresensiController::class, 'edit'])->name('presensi.edit');
    Route::delete('presensi/destroy/{date}', [PresensiController::class, 'destroy'])->name('presensi.destroy');
    Route::put('presensi/update/{date}', [PresensiController::class, 'update'])->name('presensi.update');
    Route::post('presensi/presensi/create-exit-time/{date}/{id}', [PresensiController::class, 'createExitTime'])->name('presensi.presensi.createExitTime');
    Route::post('presensi/create-exit-time/{date}/{id}', [PresensiController::class, 'createExitTime'])->name('presensi.createExitTime');
});
