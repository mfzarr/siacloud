<?php

namespace App\Http\Controllers\laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaksi\Penjualan;
use App\Models\Transaksi\Pembelian;
use App\Models\Transaksi\Beban;
use App\Models\Transaksi\Penggajian;
use Carbon\Carbon;

class LaporanCashFlowController extends Controller
{
    // Method for daily cash flow
    public function cashFlow(Request $request)
    {
        // Default to today's date for daily cash flow
        $tanggal = $request->input('tanggal', Carbon::today()->toDateString());
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        // Daily cash flow data
        $penjualan_harian = Penjualan::whereDate('tgl_transaksi', $tanggal)->sum('total');
        $pembelian_harian = Pembelian::whereDate('tanggal_pembelian', $tanggal)->sum('total');
        $pengeluaran_beban_harian = Beban::whereDate('tanggal', $tanggal)->sum('harga');
        $penggajian_harian = Penggajian::whereDate('tanggal_penggajian', $tanggal)->sum('total_gaji_bersih');

        $pemasukan_harian = $penjualan_harian;
        $pengeluaran_harian = $pembelian_harian + $pengeluaran_beban_harian + $penggajian_harian;
        $saldo_harian = $pemasukan_harian - $pengeluaran_harian;

        // Monthly cash flow data
        $penjualan_bulanan = Penjualan::whereYear('tgl_transaksi', $tahun)
            ->whereMonth('tgl_transaksi', $bulan)
            ->sum('total');
        $pembelian_bulanan = Pembelian::whereYear('tanggal_pembelian', $tahun)
            ->whereMonth('tanggal_pembelian', $bulan)
            ->sum('total');
        $pengeluaran_beban_bulanan = Beban::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->sum('harga');
        $penggajian_bulanan = Penggajian::whereYear('tanggal_penggajian', $tahun)
            ->whereMonth('tanggal_penggajian', $bulan)
            ->sum('total_gaji_bersih');

        $pemasukan_bulanan = $penjualan_bulanan;
        $pengeluaran_bulanan = $pembelian_bulanan + $pengeluaran_beban_bulanan + $penggajian_bulanan;
        $saldo_bulanan = $pemasukan_bulanan - $pengeluaran_bulanan;

        return view('laporan.cashflow.index', compact(
            'tanggal', 'bulan', 'tahun', 
            'pemasukan_harian', 'pengeluaran_harian', 'saldo_harian',
            'pemasukan_bulanan', 'pengeluaran_bulanan', 'saldo_bulanan'
        ));
    }
}
