@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Detail Pembayaran</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Pembayaran</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Detail Pembayaran</h4>
                        <p class="card-description">
                            Informasi rinci mengenai tagihan untuk penyewaan kendaraan.
                        </p>
                        @if ($tagihan->reject_notes)
                            <div class="alert alert-danger" role="alert">
                                <h4 class="alert-heading">Reject Notes:</h4>
                                <p>{{ $tagihan->reject_notes }}</p>
                            </div>
                        @endif
                        <div class="mt-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Kilometer Awal</label>
                                        <p>{{ $suratJalan->penyewaan->kilometer_awal ?? 'Tidak ada data' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Kilometer Akhir</label>
                                        <p>{{ $suratJalan->penyewaan->kilometer_akhir ?? 'Tidak ada data' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Bukti Biaya BBM TOL dan Parkir</label>
                                <div class="d-flex flex-wrap">
                                    @if ($suratJalan->bukti_biaya_bbm_tol_parkir)
                                        @foreach (json_decode($suratJalan->bukti_biaya_bbm_tol_parkir) as $bukti)
                                            <img src="{{ asset('storage/' . $bukti) }}" alt="Bukti Biaya"
                                                style="width: 150px; height: 150px; object-fit: cover; margin: 5px;">
                                        @endforeach
                                    @else
                                        <p>Tidak ada bukti yang diunggah</p>
                                    @endif
                                </div>
                            </div>
                            @if ($suratJalan->bukti_lainnya)
                                <div class="form-group">
                                    <label>Bukti Lainnya</label>
                                    <div class="d-flex flex-wrap">
                                        @foreach (json_decode($suratJalan->bukti_lainnya) as $bukti)
                                            <img src="{{ asset('storage/' . $bukti) }}" alt="Bukti Lainnya"
                                                style="width: 150px; height: 150px; object-fit: cover; margin: 5px;">
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if ($suratJalan->penyewaan->keterangan)
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <p>{{ $suratJalan->penyewaan->keterangan }}</p>
                                </div>
                            @endif
                        </div>
                        <table class="table table-bordered mt-4">
                            <tbody>
                                <tr>
                                    <td>
                                        Harga Sewa
                                        <br>
                                        <small class="text-muted">
                                            Rp {{ number_format($suratJalan->nilai_sewa, 0, ',', '.') }} x
                                            {{ $suratJalan->penyewaan->jumlah_hari_sewa }} hari
                                        </small>
                                    </td>
                                    <td class="text-right">
                                        Rp
                                        {{ number_format($suratJalan->penyewaan->nilai_sewa, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Biaya Driver
                                        <br>
                                        <small class="text-muted">
                                            Rp {{ number_format($suratJalan->biaya_driver, 0, ',', '.') }} x
                                            {{ $suratJalan->penyewaan->jumlah_hari_sewa }} hari
                                        </small>
                                    </td>
                                    <td class="text-right">
                                        Rp
                                        {{ number_format($suratJalan->penyewaan->biaya_driver, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Biaya BBM</td>
                                    <td class="text-right">
                                        Rp {{ number_format($suratJalan->penyewaan->biaya_bbm, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Biaya TOL</td>
                                    <td class="text-right">
                                        Rp {{ number_format($suratJalan->penyewaan->biaya_tol, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Biaya Parkir</td>
                                    <td class="text-right">
                                        Rp {{ number_format($suratJalan->penyewaan->biaya_parkir, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @if ($suratJalan->is_lebih_hari)
                                    <tr>
                                        <td>
                                            Denda Kelebihan Hari
                                            <br>
                                            <small class="text-muted">
                                                Rp {{ number_format($suratJalan->denda, 0, ',', '.') }} x
                                                {{ $suratJalan->lebih_hari }} hari
                                            </small>
                                        </td>
                                        <td class="text-right">
                                            Rp
                                            {{ number_format($suratJalan->jumlah_denda, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif
                                <tr class="font-weight-bold">
                                    <td>Total Biaya</td>
                                    <td class="text-right">
                                        Rp {{ number_format($suratJalan->penyewaan->total_biaya, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-4">
                            <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-light">Kembali</a>
                            @if ($tagihan->status == 'Pengajuan Pembayaran')
                                <button type="button" class="btn btn-danger" id="rejectButton">Reject</button>
                                <button type="button" class="btn btn-success" id="approveButton">Approve</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('rejectButton').addEventListener('click', function() {
            Swal.fire({
                title: 'Reject Pengajuan Pembayaran',
                input: 'textarea',
                inputLabel: 'Notes',
                inputPlaceholder: 'Masukan catatan disini...',
                inputAttributes: {
                    'aria-label': 'Masukan catatan disini'
                },
                showCancelButton: true,
                confirmButtonText: 'Reject',
                cancelButtonText: 'Close',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Tolong masukkan catatan!'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const notes = result.value;
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('admin.pembayaran.decline', $tagihan->id) }}';

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    const notesInput = document.createElement('input');
                    notesInput.type = 'hidden';
                    notesInput.name = 'reject_notes';
                    notesInput.value = notes;
                    form.appendChild(notesInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });

        document.getElementById('approveButton').addEventListener('click', function() {
            Swal.fire({
                title: 'Approve Pengajuan Pembayaran',
                text: 'Apakah Anda yakin ingin menyetujui pengajuan pembayaran ini?',
                showCancelButton: true,
                confirmButtonText: 'Approve',
                cancelButtonText: 'Close',
                preConfirm: () => {
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('admin.pembayaran.approve', $tagihan->id) }}';

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    </script>
@endsection
