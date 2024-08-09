@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Dashboard Pemeliharaan dan Aset</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pemeliharaan dan Aset</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Jumlah Peminjaman per Bulan</h4>
                        <canvas id="peminjamanChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Anggaran Peminjaman per Bulan</h4>
                        <canvas id="anggaranChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Vendor Terbanyak</h4>
                        <canvas id="vendorChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-sm-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Akumulasi Pengajuan per Tahun</h4>
                        <canvas id="pengajuanChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Utilisasi Kendaraan</h4>
                        <canvas id="utilisasiChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Pengeluaran Operasional</h4>
                        <canvas id="pengeluaranChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function getMonthNames() {
            return ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        }

        new Chart(document.getElementById('peminjamanChart'), {
            type: 'bar',
            data: {
                labels: getMonthNames(),
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: {!! json_encode($peminjamanPerBulan->pluck('jumlah')) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.6)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        new Chart(document.getElementById('anggaranChart'), {
            type: 'bar',
            data: {
                labels: getMonthNames(),
                datasets: [{
                    label: 'Anggaran Peminjaman',
                    data: {!! json_encode($anggaranPerBulan->pluck('total')) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.6)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        new Chart(document.getElementById('vendorChart'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($vendorTerbanyak->pluck('vendor.nama')) !!},
                datasets: [{
                    data: {!! json_encode($vendorTerbanyak->pluck('jumlah')) !!},
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)'
                    ]
                }]
            },
            options: {
                responsive: true
            }
        });

        new Chart(document.getElementById('pengajuanChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($pengajuanPerTahun->pluck('tahun')) !!},
                datasets: [{
                    label: 'Approved',
                    data: {!! json_encode($pengajuanPerTahun->pluck('approved')) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.6)'
                }, {
                    label: 'Declined',
                    data: {!! json_encode($pengajuanPerTahun->pluck('declined')) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.6)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true
                    }
                }
            }
        });

        new Chart(document.getElementById('utilisasiChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($utilisasiKendaraan->pluck('id')) !!},
                datasets: [{
                    label: 'Utilisasi (%)',
                    data: {!! json_encode(
                        $utilisasiKendaraan->pluck('utilisasi')->map(function ($value) {
                            return $value * 100;
                        }),
                    ) !!},
                    borderColor: 'rgba(153, 102, 255, 1)',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

        new Chart(document.getElementById('pengeluaranChart'), {
            type: 'bar',
            data: {
                labels: getMonthNames(),
                datasets: [{
                    label: 'BBM',
                    data: {!! json_encode($pengeluaranOperasional->pluck('bbm')) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.6)'
                }, {
                    label: 'Tol',
                    data: {!! json_encode($pengeluaranOperasional->pluck('tol')) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.6)'
                }, {
                    label: 'Parkir',
                    data: {!! json_encode($pengeluaranOperasional->pluck('parkir')) !!},
                    backgroundColor: 'rgba(255, 206, 86, 0.6)'
                }, {
                    label: 'Driver',
                    data: {!! json_encode($pengeluaranOperasional->pluck('driver')) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.6)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true
                    }
                }
            }
        });
    </script>
@endsection
