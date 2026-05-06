@extends('layouts.master')

@section('title', 'Riwayat Transaksi Lengkap')

@section('styles')
<style>
    .history-container { max-width: 1000px; margin: 0 auto; }
    
    /* Desain Card Filter */
    .filter-card {
        background: #ffffff;
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    /* Memaksa format dd/mm/yyyy secara visual pada beberapa browser */
    input[type="date"] {
        position: relative;
    }
    
    /* Desain List Transaksi */
    .transaction-list { background: transparent; border: none; }
    
    .transaction-item { 
        background: white;
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        padding: 18px 25px; 
        border-radius: 12px;
        margin-bottom: 12px;
        border: 1px solid #f0f0f0;
        border-left: 5px solid #007bff; 
        transition: all 0.3s ease;
    }

    .transaction-item:hover { 
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .trans-id { font-size: 1.1rem; font-weight: 700; color: #2c3e50; margin-bottom: 3px; }
    .trans-date { color: #7f8c8d; font-size: 0.85rem; font-weight: 500; }
    .trans-total { font-size: 1.1rem; font-weight: 700; color: #27ae60; }

    .btn-detail { 
        background: #f1f7fe; 
        color: #007bff; 
        border: 1px solid #e1effe; 
        width: 42px; 
        height: 42px; 
        border-radius: 10px; 
        transition: 0.3s;
    }

    .btn-detail:hover { background: #007bff; color: white; border-color: #007bff; }

    .badge-periode {
        background: #e1f0ff;
        color: #007bff;
        padding: 8px 15px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .date-input-custom {
        position: relative;
        color: transparent !important;
    }

    .date-input-custom::before {
        position: absolute;
        top: 50%;
        left: 12px;
        transform: translateY(-50%);
        content: attr(data-date);
        color: #495057; /* Warna teks tanggal */
        pointer-events: none;
    }

    /* Tetap munculkan ikon kalender di kanan */
    .date-input-custom::-webkit-calendar-picker-indicator {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: transparent;
        cursor: pointer;
        z-index: 1;
    }

    /* Warna Status & Metode */
    .badge-status {
        font-size: 0.7rem;
        padding: 4px 12px;
        border-radius: 50px;
        font-weight: 700;
        text-transform: uppercase;
        margin-left: 10px;
    }
    .status-success { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
    .status-pending { background: #fff8e1; color: #f57f17; border: 1px solid #ffecb3; }
    
    .method-tag {
        font-size: 0.75rem;
        color: #546e7a;
        background: #eceff1;
        padding: 3px 10px;
        border-radius: 6px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    /* Jika input tidak memiliki value, buat teks data-date jadi abu-abu */
    .date-input-custom[value=""]::before {
        color: #adb5bd !important; /* Warna abu-abu seperti placeholder */
        font-style: italic;
    }

    /* Modifikasi border kiri berdasarkan status */
    .border-success { border-left: 5px solid #28a745 !important; }
    .border-pending { border-left: 5px solid #ffc107 !important; }

    /* Modal Styling */
    .modal-content { border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    .modal-header { border-bottom: 1px solid #f8f9fa; padding: 25px; }
    .modal-body { padding: 25px; }
    .divider { border-top: 1px dashed #ebedef; margin: 18px 0; }
    .item-row { display: flex; justify-content: space-between; padding: 5px 0; font-size: 0.95rem; }
</style>
@endsection

@section('content')
<div class="history-container">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1">Riwayat Transaksi</h2>
            <p class="text-muted mb-0">Kelola dan pantau seluruh transaksi toko Anda</p>
        </div>
        <div class="badge-periode shadow-sm">
            <i class="fas fa-calendar-alt me-2"></i>
            @if(request('start_date') && request('end_date'))
                {{ \Carbon\Carbon::parse(request('start_date'))->setTimezone('Asia/Jakarta')->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse(request('end_date'))->setTimezone('Asia/Jakarta')->translatedFormat('d M Y') }}
            @else
                Semua Data
            @endif
            <span class="ms-2 badge bg-primary text-white">{{ $transaksi->count() }}</span>
        </div>
    </div>

    <div class="card filter-card mb-4">
        <div class="card-body p-4">
            <form action="{{ route('laporan_barang.riwayat_seluruhnya') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">Tanggal Awal</label>
                    <input type="date" name="start_date" class="form-control date-input-custom" 
                        value="{{ $startDate ?? '' }}" 
                        data-date="{{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : $placeholderStart }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control date-input-custom" 
                        value="{{ $endDate ?? '' }}" 
                        data-date="{{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : $placeholderEnd }}">
                </div>
                <!-- TAMBAHKAN FILTER METODE DI SINI -->
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">Metode</label>
                    <select name="metode" class="form-select shadow-sm">
                        <option value="">Semua Metode</option>
                        <option value="tunai" {{ request('metode') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="qris" {{ request('metode') == 'qris' ? 'selected' : '' }}>QRIS</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary shadow-sm w-100">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                        @if(request('start_date') || request('end_date') || request('metode'))
                            <a href="{{ route('laporan_barang.riwayat_seluruhnya') }}" class="btn btn-light shadow-sm" title="Reset">
                                <i class="fas fa-undo"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if ($transaksi->count() > 0)
        <div class="transaction-list">
            @foreach ($transaksi as $trans)
                <div class="transaction-item {{ $trans->status_pembayaran == 'success' ? 'border-success' : 'border-pending' }}">
                    <div class="trans-info-left">
                        <div class="d-flex align-items-center mb-1">
                            <span class="trans-id">TRX-{{ str_pad($trans->id, 3, '0', STR_PAD_LEFT) }}</span>
                            <!-- Badge Status -->
                            <span class="badge-status {{ $trans->status_pembayaran == 'success' ? 'status-success' : 'status-pending' }}">
                                {{ $trans->status_pembayaran }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <!-- Tag Metode -->
                            <span class="method-tag">
                                <i class="fas {{ $trans->metode_pembayaran == 'qris' ? 'fa-qrcode' : 'fa-money-bill-wave' }}"></i>
                                {{ strtoupper($trans->metode_pembayaran) }}
                            </span>
                            <span class="trans-date">
                                <i class="far fa-clock me-1"></i> 
                                {{ \Carbon\Carbon::parse($trans->tanggal)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y | H:i') }} WIB
                            </span>
                        </div>
                    </div>
                    
                    <div class="trans-right">
                        <span class="trans-total">Rp {{ number_format($trans->total_harga, 0, ',', '.') }}</span>
                        <!-- Tambahkan parameter status & metode ke fungsi showDetail -->
                        <button class="btn-detail ms-3" 
                                onclick="showDetail(
                                    '{{ $trans->id }}', 
                                    '{{ \Carbon\Carbon::parse($trans->tanggal)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y | H:i') }} WIB', 
                                    '{{ number_format($trans->total_harga, 0, ',', '.') }}', 
                                    '{{ number_format($trans->total_bayar, 0, ',', '.') }}', 
                                    '{{ number_format($trans->kembalian, 0, ',', '.') }}', 
                                    '{{ $trans->detail }}',
                                    '{{ $trans->status_pembayaran }}',
                                    '{{ $trans->metode_pembayaran }}'
                                )">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 bg-white rounded-4 shadow-sm mt-4">
            <img src="https://illustrations.popsy.co/gray/box.svg" alt="empty" style="width: 150px;" class="mb-3">
            <h5 class="text-muted">Tidak ada transaksi ditemukan</h5>
            <p class="small text-secondary">Coba ubah filter tanggal Anda</p>
        </div>
    @endif
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center fw-bold">Rincian Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between mb-4">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">ID Transaksi</div>
                        <div id="modal-id" class="fw-bold h5 text-primary"></div>
                    </div>
                    <div class="text-end">
                        <div class="text-muted small text-uppercase fw-bold">Metode Pembayaran</div>
                        <div id="modal-metode" class="fw-bold text-dark text-uppercase"></div>
                    </div>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Status</div>
                        <div class="d-flex align-items-center gap-2">
                            {{-- <div id="modal-id" class="fw-bold h5 text-primary mb-0"></div> --}}
                            <span id="modal-status" class="badge-status"></span>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="text-muted small text-uppercase fw-bold">Waktu</div>
                        <div id="modal-date" class="fw-bold text-dark"></div>
                    </div>
                </div>

                <div class="fw-bold mb-3"><i class="fas fa-shopping-basket me-2 text-primary"></i>Item Pembelian</div>
                <div id="modal-items-list" class="bg-light p-3 rounded-3 mb-3">
                    </div>

                <div class="divider"></div>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Pembelian</span>
                    <span id="modal-total" class="fw-bold text-dark"></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Uang Dibayar</span>
                    <span id="modal-bayar" class="text-dark"></span>
                </div>
                <div class="d-flex justify-content-between mt-3 p-3 rounded-3 bg-success bg-opacity-10">
                    <span class="text-success fw-bold">Uang Kembalian</span>
                    <span id="modal-kembali" class="text-success fw-bold h5 mb-0"></span>
                </div>

                <button type="button" class="btn btn-dark w-100 py-3 rounded-3 mt-4 fw-bold" data-bs-dismiss="modal">TUTUP RINCIAN</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('.date-input-custom').on('change', function() {
        let val = $(this).val();
        if (val) {
            let date = new Date(val);
            let d = ("0" + date.getDate()).slice(-2);
            let m = ("0" + (date.getMonth() + 1)).slice(-2);
            let y = date.getFullYear();
            $(this).attr('data-date', d + '/' + m + '/' + y);
        } else {
            $(this).attr('data-date', 'dd/mm/yyyy');
        }
    });
    function showDetail(id, date, total, bayar, kembali, itemsJson, status, metode) {
        document.getElementById('modal-id').innerText = 'TRX-' + id.padStart(3, '0');
        document.getElementById('modal-date').innerText = date;
        document.getElementById('modal-total').innerText = 'Rp ' + total;
        document.getElementById('modal-bayar').innerText = 'Rp ' + bayar;
        document.getElementById('modal-kembali').innerText = 'Rp ' + kembali;
        
        // Tampilkan Metode & Status
        document.getElementById('modal-metode').innerText = metode;
        const statusElem = document.getElementById('modal-status');
        statusElem.innerText = status;
        statusElem.className = 'badge-status ' + (status === 'success' ? 'status-success' : 'status-pending');

        const items = JSON.parse(itemsJson);
        let itemsHtml = '';
        items.forEach(item => {
            const namaBarang = item.barang ? item.barang.nama : 'Produk';
            const subtotal = item.qty * item.harga_satuan;
            itemsHtml += `
                <div class="item-row">
                    <span class="text-dark">${namaBarang} <small class="text-muted">(${item.qty}x)</small></span>
                    <span class="fw-bold">Rp ${subtotal.toLocaleString('id-ID')}</span>
                </div>`;
        });
        document.getElementById('modal-items-list').innerHTML = itemsHtml;
        var myModal = new bootstrap.Modal(document.getElementById('detailModal'));
        myModal.show();
    }
</script>
@endsection