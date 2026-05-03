@extends('layouts.master')

@section('title', 'Riwayat Transaksi')

@section('styles')
<style>
    .history-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    /* List Style Baru (Gambar Kiri) */
    .transaction-list {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .transaction-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        border-left: 4px solid #28a745;
        transition: background 0.2s;
    }

    .transaction-item:hover {
        background: #f8f9fa;
    }

    .trans-info-left {
        display: flex;
        flex-direction: column;
    }

    .trans-id {
        font-weight: bold;
        color: #333;
        font-size: 1rem;
    }

    .trans-time {
        color: #888;
        font-size: 0.85rem;
    }

    .trans-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .trans-total {
        font-weight: bold;
        color: #333;
    }

    .btn-detail {
        background: #e3f2fd;
        color: #2196f3;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-detail:hover {
        background: #2196f3;
        color: white;
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 12px;
        border: none;
    }
    .modal-header {
        border-bottom: 1px solid #f0f0f0;
        padding: 20px;
    }
    .modal-body {
        padding: 20px;
    }
    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 0.95rem;
    }
    .divider {
        border-top: 1px dashed #ddd;
        margin: 15px 0;
    }

    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .btn-kembali {
        background: #6c757d;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .btn-delete-trans {
        color: #dc3545;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        margin-left: 10px;
    }
    /* Warna Status & Metode */
    .badge-status {
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 50px;
        font-weight: bold;
        text-transform: uppercase;
    }
    .status-success { background: #d4edda; color: #155724; }
    .status-pending { background: #fff3cd; color: #856404; }
    
    .method-icon {
        font-size: 0.85rem;
        color: #555;
        background: #f0f0f0;
        padding: 3px 8px;
        border-radius: 4px;
        margin-right: 8px;
    }
</style>
@endsection

@section('content')
<div class="history-container">
    <div class="header-section">
        <h1><i class="fas fa-history me-2"></i>Riwayat Transaksi Hari Ini</h1>
        <a href="/kasir" class="btn-kembali">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke POS
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($transaksi->count() > 0)
        <div class="alert alert-primary mb-3">
            <strong>Total Transaksi Hari Ini: {{ $transaksi->count() }}</strong>
        </div>

        <div class="transaction-list">
            @foreach ($transaksi as $trans)
                <div class="transaction-item" style="border-left: 4px solid {{ $trans->status_pembayaran == 'success' ? '#28a745' : '#ffc107' }}">
                    <div class="trans-info-left">
                        <span class="trans-id">TRX-{{ str_pad($trans->id, 3, '0', STR_PAD_LEFT) }}</span>
                        <div class="d-flex align-items-center mt-1">
                            <!-- Menampilkan Metode -->
                            <span class="method-icon">
                                <i class="fas {{ $trans->metode_pembayaran == 'qris' ? 'fa-qrcode' : 'fa-money-bill-wave' }} me-1"></i>
                                {{ strtoupper($trans->metode_pembayaran) }}
                            </span>
                            <!-- Menampilkan Status -->
                            <span class="badge-status {{ $trans->status_pembayaran == 'success' ? 'status-success' : 'status-pending' }}">
                                {{ $trans->status_pembayaran }}
                            </span>
                        </div>
                        <span class="trans-time mt-1"><i class="fas fa-clock me-1"></i>
                            {{ \Carbon\Carbon::parse($trans->tanggal)->setTimezone('Asia/Jakarta')->format('H:i:s') }}</span>
                    </div>
                    
                    <div class="trans-right">
                        <span class="trans-total">Rp {{ number_format($trans->total_harga, 0, ',', '.') }}</span>
                        
                        <div class="d-flex align-items-center">
                            <!-- Tambahkan parameter status dan metode ke fungsi showDetail -->
                            <button class="btn-detail" 
                                    onclick="showDetail('{{ $trans->id }}', '{{ \Carbon\Carbon::parse($trans->tanggal)->setTimezone('Asia/Jakarta')->format('d/m/Y | H:i:s') }}', '{{ number_format($trans->total_harga, 0, ',', '.') }}', '{{ number_format($trans->total_bayar, 0, ',', '.') }}', '{{ number_format($trans->kembalian, 0, ',', '.') }}', '{{ $trans->detail }}', '{{ $trans->status_pembayaran }}', '{{ $trans->metode_pembayaran }}')">
                                <i class="fas fa-search"></i>
                            </button>

                            <form method="POST" action="/kasir/{{ $trans->id }}" class="delete-form ms-3" onsubmit="return confirm('Hapus transaksi ini? Stok barang akan dikembalikan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete-trans" title="Hapus Transaksi">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 bg-white rounded shadow-sm">
            <i class="fas fa-inbox fa-3x text-light mb-3"></i>
            <p class="text-muted">Tidak ada transaksi pada hari ini</p>
        </div>
    @endif
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center fw-bold">Detail Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <p class="mb-0 text-muted">Transaksi: <span id="modal-id" class="text-dark fw-bold"></span></p>
                    <p class="text-muted">Tanggal: <span id="modal-date" class="text-dark"></span></p>
                    <p class="mb-0 text-muted">Metode: <span id="modal-metode" class="text-dark fw-bold text-uppercase"></span></p>
                    <p class="text-muted">Status: <span id="modal-status" class="badge-status"></span></p>
                </div>

                <div class="fw-bold mb-2">Item Pembelian:</div>
                <div id="modal-items-list">
                    </div>

                <div class="divider"></div>

                <div class="detail-row fw-bold">
                    <span>Total:</span>
                    <span id="modal-total"></span>
                </div>
                <div class="detail-row text-muted">
                    <span>Bayar:</span>
                    <span id="modal-bayar"></span>
                </div>
                <div class="detail-row text-success fw-bold">
                    <span>Kembali:</span>
                    <span id="modal-kembali"></span>
                </div>

                <div class="text-center mt-4">
                    <button type="button" class="btn btn-success w-100 py-2" data-bs-dismiss="modal">TUTUP</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showDetail(id, date, total, bayar, kembali, itemsJson, status, metode) {
        // Info Dasar
        document.getElementById('modal-id').innerText = 'TRX-' + id.padStart(3, '0');
        document.getElementById('modal-date').innerText = date;
        document.getElementById('modal-total').innerText = 'Rp ' + total;
        document.getElementById('modal-bayar').innerText = 'Rp ' + bayar;
        document.getElementById('modal-kembali').innerText = 'Rp ' + kembali;
        
        // Set Metode & Status
        document.getElementById('modal-metode').innerText = metode;
        const statusElem = document.getElementById('modal-status');
        statusElem.innerText = status;
        
        // Atur Warna Status di Modal
        statusElem.className = 'badge-status ' + (status === 'success' ? 'status-success' : 'status-pending');

        // Render Items
        const items = JSON.parse(itemsJson);
        let itemsHtml = '';
        items.forEach(item => {
            const namaBarang = item.barang ? item.barang.nama : 'Produk';
            const subtotal = item.qty * item.harga_satuan;
            itemsHtml += `
                <div class="text-muted small">
                    ${namaBarang} (${item.qty}x) = Rp ${subtotal.toLocaleString('id-ID')}
                </div>`;
        });
        document.getElementById('modal-items-list').innerHTML = itemsHtml;

        // Tampilkan Modal
        var myModal = new bootstrap.Modal(document.getElementById('detailModal'));
        myModal.show();
    }
</script>
@endsection