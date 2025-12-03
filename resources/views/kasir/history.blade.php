@extends('layouts.master')

@section('title', 'Riwayat Transaksi')

@section('styles')
<style>
    .history-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    .transaction-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
        border-left: 5px solid #28a745;
    }

    .transaction-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e9ecef;
    }

    .transaction-id {
        font-weight: bold;
        color: #333;
        font-size: 1.1rem;
    }

    .transaction-time {
        color: #666;
        font-size: 0.9rem;
    }

    .transaction-items {
        margin-bottom: 15px;
    }

    .item-detail {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        color: #555;
        font-size: 0.95rem;
    }

    .item-detail:not(:last-child) {
        border-bottom: 1px solid #f0f0f0;
    }

    .item-name {
        flex: 1;
    }

    .item-qty {
        width: 60px;
        text-align: center;
    }

    .item-price {
        width: 100px;
        text-align: right;
    }

    .transaction-summary {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 15px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .summary-row:last-child {
        margin-bottom: 0;
        font-weight: bold;
        color: #28a745;
        font-size: 1.1rem;
        padding-top: 8px;
        border-top: 1px solid #dee2e6;
    }

    .transaction-footer {
        display: flex;
        gap: 10px;
        justify-content: space-between;
        align-items: center;
    }

    .btn-delete-transaction {
        background: #dc3545;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.2s;
        font-weight: 600;
    }

    .btn-delete-transaction:hover {
        background: #c82333;
    }

    .btn-kembali {
        background: #6c757d;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.2s;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
    }

    .btn-kembali:hover {
        background: #545b62;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .empty-state-icon {
        font-size: 3rem;
        color: #ccc;
        margin-bottom: 15px;
    }

    .empty-state-text {
        color: #666;
        font-size: 1.1rem;
    }

    .success-alert {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .cambium-display {
        background: #fff3cd;
        border: 1px solid #ffc107;
        color: #856404;
        padding: 10px;
        border-radius: 4px;
        font-weight: 600;
    }

    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .header-section h1 {
        margin: 0;
    }

    .delete-form {
        display: inline;
    }

    @media (max-width: 768px) {
        .transaction-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .transaction-footer {
            flex-direction: column;
        }

        .btn-delete-transaction,
        .btn-kembali {
            width: 100%;
        }

        .item-detail {
            flex-direction: column;
        }

        .item-qty,
        .item-price {
            width: auto;
            text-align: left;
        }
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
        <div class="success-alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if ($transaksi->count() > 0)
        <div style="margin-bottom: 20px; padding: 15px; background: #e3f2fd; border-radius: 6px; border-left: 4px solid #2196f3;">
            <strong>Total Transaksi Hari Ini: {{ $transaksi->count() }}</strong>
        </div>

        @foreach ($transaksi as $trans)
            <div class="transaction-card">
                <div class="transaction-header">
                    <div>
                        <div class="transaction-id">#{{ str_pad($trans->id, 6, '0', STR_PAD_LEFT) }}</div>
                        <div class="transaction-time">
                            <i class="fas fa-clock me-2"></i>{{ \Carbon\Carbon::parse($trans->tanggal)->setTimezone('Asia/Jakarta')->format('H:i:s') }}
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <span style="background: #28a745; color: white; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem;">
                            <i class="fas fa-check-circle me-1"></i>Selesai
                        </span>
                    </div>
                </div>

                <div class="transaction-items">
                    <div style="font-weight: 600; margin-bottom: 10px; color: #333;">
                        <i class="fas fa-list me-2"></i>Item Pembelian
                    </div>
                    @foreach ($trans->detail as $detail)
                        <div class="item-detail">
                            <div class="item-name">
                                <strong>{{ $detail->barang->nama }}</strong>
                                <br>
                                <small style="color: #999;">{{ $detail->barang->kode_barang }}</small>
                            </div>
                            <div class="item-qty">{{ $detail->qty }} x</div>
                            <div class="item-price">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="transaction-summary">
                    <div class="summary-row">
                        <span>Total Harga:</span>
                        <span>Rp {{ number_format($trans->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Uang Masuk:</span>
                        <span>Rp {{ number_format($trans->total_bayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Kembalian:</span>
                        <span>Rp {{ number_format($trans->kembalian, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="transaction-footer">
                    <div style="color: #666; font-size: 0.9rem;">
                        <i class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($trans->tanggal)->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }}
                    </div>
                    <form method="POST" action="/kasir/{{ $trans->id }}" class="delete-form" onsubmit="return confirm('Hapus transaksi ini? Stok barang akan dikembalikan.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete-transaction">
                            <i class="fas fa-trash me-2"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-inbox"></i>
            </div>
            <p class="empty-state-text">Tidak ada transaksi pada hari ini</p>
            <a href="/kasir" class="btn-kembali" style="margin-top: 15px;">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke POS
            </a>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // Konfirmasi sebelum delete
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Apakah Anda yakin ingin menghapus transaksi ini? Stok barang akan dikembalikan.')) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection
