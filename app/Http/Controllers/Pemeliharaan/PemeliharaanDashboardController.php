<?php

namespace App\Http\Controllers\Pemeliharaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penyewaan;
use App\Models\Vendor;
use App\Models\Kendaraan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PemeliharaanDashboardController extends Controller
{
    public function index()
    {
        $currentYear = Carbon::now()->year;
        $startDate = Carbon::create($currentYear, 1, 1);
        $endDate = Carbon::create($currentYear, 12, 31);

        $peminjamanPerBulan = Penyewaan::select(
            DB::raw('MONTH(tanggal_mulai) as bulan'),
            DB::raw('COUNT(*) as jumlah')
        )
            ->whereBetween('tanggal_mulai', [$startDate, $endDate])
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $anggaranPerBulan = Penyewaan::select(
            DB::raw('MONTH(tanggal_mulai) as bulan'),
            DB::raw('SUM(total_biaya) as total')
        )
            ->whereBetween('tanggal_mulai', [$startDate, $endDate])
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $vendorTerbanyak = Penyewaan::select('id_vendor', DB::raw('COUNT(*) as jumlah'))
            ->whereBetween('tanggal_mulai', [$startDate, $endDate])
            ->whereNotNull('id_vendor')
            ->groupBy('id_vendor')
            ->orderByDesc('jumlah')
            ->limit(5)
            ->with('vendor')
            ->get();

        $pengajuanPerTahun = Penyewaan::select(
            DB::raw('YEAR(created_at) as tahun'),
            DB::raw('SUM(CASE 
                WHEN status IN ("Approved by Fasilitas", "Approved by Administrasi", "Approved by Vendor", "Surat Jalan") THEN 1 
                ELSE 0 END) as approved'),
            DB::raw('SUM(CASE 
                WHEN status IN ("Rejected by Vendor", "Rejected by Fasilitas") THEN 1 
                ELSE 0 END) as declined')
        )
            ->groupBy('tahun')
            ->orderBy('tahun')
            ->get();

        $utilisasiKendaraan = Kendaraan::select(
            'kendaraan.id',
            'kendaraan.nama',
            DB::raw('(SUM(DATEDIFF(penyewaan.tanggal_selesai, penyewaan.tanggal_mulai)) / (DATEDIFF(?, ?) * COUNT(DISTINCT kendaraan.id))) as utilisasi')
        )
            ->leftJoin('penyewaan', 'kendaraan.id', '=', 'penyewaan.id_kendaraan')
            ->whereBetween('penyewaan.tanggal_mulai', [$startDate, $endDate])
            ->groupBy('kendaraan.id', 'kendaraan.nama')
            ->addBinding([$endDate, $startDate], 'select')
            ->get();

        $pengeluaranOperasional = Penyewaan::select(
            DB::raw('MONTH(tanggal_mulai) as bulan'),
            DB::raw('SUM(biaya_bbm) as bbm'),
            DB::raw('SUM(biaya_tol) as tol'),
            DB::raw('SUM(biaya_parkir) as parkir'),
            DB::raw('SUM(biaya_driver) as driver')
        )
            ->whereBetween('tanggal_mulai', [$startDate, $endDate])
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        return view('pemeliharaan.dashboard.index', compact(
            'peminjamanPerBulan',
            'anggaranPerBulan',
            'vendorTerbanyak',
            'pengajuanPerTahun',
            'utilisasiKendaraan',
            'pengeluaranOperasional'
        ));
    }
}