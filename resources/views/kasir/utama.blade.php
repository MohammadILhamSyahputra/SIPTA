@extends('layouts.master')

@section('title', 'Kasir - POS')

@section('styles')
<style>
    .kasir-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    .item-grid {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
    }

    .item-row {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr auto;
        gap: 12px;
        margin-bottom: 12px;
        align-items: center;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 6px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .item-row.filled {
        border-color: #28a745;
        background: #f0fff4;
    }

    .item-row.empty {
        border-color: #dc3545;
        background: #fff5f5;
    }

    .search-input {
        position: relative;
    }

    .search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #ddd;
        border-top: none;
        border-radius: 0 0 6px 6px;
        max-height: 300px;
        overflow-y: auto;
        z-index: 1000;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        display: none;
    }

    .search-results.show {
        display: block;
    }

    .search-item {
        padding: 10px 12px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.2s;
    }

    .search-item:hover {
        background: #f8f9fa;
    }

    .search-item-name {
        font-weight: 500;
        color: #333;
    }

    .search-item-code {
        font-size: 0.85rem;
        color: #666;
    }

    .search-item-price {
        font-size: 0.85rem;
        color: #28a745;
        font-weight: 600;
    }

    .summary-box {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #e9ecef;
        font-size: 1.1rem;
    }

    .summary-row:last-child {
        border-bottom: none;
        font-size: 1.3rem;
        font-weight: bold;
        color: #28a745;
        padding-top: 15px;
    }

    .btn-group-action {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-delete {
        padding: 8px 12px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.2s;
        font-size: 0.9rem;
    }

    .btn-delete:hover {
        background: #c82333;
    }

    .btn-action {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-transaksi {
        background: #28a745;
        color: white;
        flex: 1;
    }

    .btn-transaksi:hover {
        background: #218838;
    }

    .btn-transaksi:disabled {
        background: #ccc;
        cursor: not-allowed;
    }

    .btn-riwayat {
        background: #007bff;
        color: white;
        flex: 1;
    }

    .btn-riwayat:hover {
        background: #28a745;
    }

    .btn-kembali {
        background: #6c757d;
        color: white;
        flex: 1;
    }

    .btn-kembali:hover {
        background: #545b62;
    }

    .qty-error {
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 3px;
        padding: 4px 8px;
        background: #fff5f5;
        border-left: 3px solid #dc3545;
        border-radius: 2px;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 2000;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 8px;
        padding: 30px;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    }

    .modal-header {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 20px;
        color: #333;
    }

    .form-group-modal {
        margin-bottom: 20px;
    }

    .form-group-modal label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }

    .form-group-modal input {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 1rem;
        box-sizing: border-box;
    }

    .form-group-modal input:focus {
        outline: none;
        border-color: #28a745;
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
    }

    .modal-footer {
        display: flex;
        gap: 10px;
        margin-top: 25px;
    }

    .modal-footer button {
        flex: 1;
        padding: 12px;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-cancel {
        background: #e9ecef;
        color: #333;
    }

    .btn-cancel:hover {
        background: #dee2e6;
    }

    .btn-confirm {
        background: #28a745;
        color: white;
    }

    .btn-confirm:hover {
        background: #218838;
    }

    .error-message {
        color: #dc3545;
        font-size: 0.9rem;
        margin-top: 5px;
    }

    .success-alert {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .input-qty, .input-barang {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 0.95rem;
    }

    .input-qty:focus, .input-barang:focus {
        outline: none;
        border-color: #28a745;
    }

    .result-text {
        margin-top: 15px;
        padding: 15px;
        background: #e7f5ff;
        border-left: 4px solid #0066cc;
        border-radius: 4px;
        color: #0066cc;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .item-row {
            grid-template-columns: 1fr;
        }

        .item-grid {
            padding: 12px;
        }

        .summary-box {
            padding: 12px;
        }
    }
</style>
@endsection

@section('content')
<div class="kasir-container">
    <h1 class="mb-4">
        <i class="fas fa-cash-register me-2"></i>Point of Sale (POS)
    </h1>

    @if (session('success'))
        <div class="success-alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- Item Grid -->
    <div class="item-grid">
        <h5 class="mb-3">
            <i class="fas fa-shopping-cart me-2"></i>Daftar Item Transaksi
        </h5>
        <div id="items-container">
            <!-- First empty row -->
            <div class="item-row empty" data-row-index="0">
                <div class="search-input">
                    <input 
                        type="text" 
                        class="form-control input-barang" 
                        placeholder="Cari nama atau kode barang..."
                        data-row-index="0"
                    >
                    <div class="search-results" data-row-index="0"></div>
                    <input type="hidden" class="item-id" value="">
                </div>
                <div>
                    <input 
                        type="number" 
                        class="form-control input-qty" 
                        placeholder="Qty"
                        min="1"
                        data-row-index="0"
                    >
                </div>
                <div>
                    <input 
                        type="text" 
                        class="form-control harga-satuan" 
                        placeholder="Harga"
                        readonly
                    >
                </div>
                <div>
                    <input 
                        type="text" 
                        class="form-control subtotal" 
                        placeholder="Subtotal"
                        readonly
                    >
                </div>
                <div>
                    <button type="button" class="btn-delete" style="display:none;">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary -->
    <div class="summary-box">
        <div class="summary-row">
            <span>Total Item:</span>
            <span id="total-items">0</span>
        </div>
        <div class="summary-row">
            <span>Total Harga:</span>
            <span id="total-harga">Rp 0</span>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="btn-group-action">
        <button class="btn-action btn-transaksi" id="btn-transaksi" disabled>
            <i class="fas fa-check-circle me-2"></i>Transaksi
        </button>
        <button class="btn-action btn-riwayat" id="btn-riwayat">
            <i class="fas fa-history me-2"></i>Riwayat
        </button>
        <a href="{{ route('dashboard') }}" class="btn-action btn-kembali">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<!-- Modal Pembayaran -->
<div class="modal-overlay" id="modal-pembayaran">
    <div class="modal-content">
        <div class="modal-header">
            <i class="fas fa-money-bill-wave me-2"></i>Input Pembayaran
        </div>
        <form id="form-pembayaran">
            <div class="form-group-modal">
                <label for="total-bayar">Total Harga:</label>
                <input 
                    type="text" 
                    id="display-total-harga" 
                    class="form-control-disabled" 
                    readonly
                    style="background: #e9ecef; cursor: not-allowed;"
                >
            </div>
            <div class="form-group-modal">
                <label for="uang-masuk">Uang yang Dimasukkan:</label>
                <input 
                    type="number" 
                    id="uang-masuk" 
                    class="form-control" 
                    placeholder="Masukkan nominal uang"
                    min="0"
                    step="1"
                >
                <div class="error-message" id="error-uang"></div>
            </div>
            <div class="form-group-modal">
                <label for="kembalian">Kembalian:</label>
                <input 
                    type="text" 
                    id="kembalian-display" 
                    class="form-control-disabled" 
                    readonly
                    style="background: #e9ecef; cursor: not-allowed; color: #28a745; font-weight: bold;"
                >
            </div>
            <div class="result-text" id="result-text" style="display: none;"></div>
            <div class="modal-footer">
                <button type="button" class="modal-footer button btn-cancel" id="btn-cancel-pembayaran">
                    <i class="fas fa-times me-2"></i>Batal
                </button>
                <button type="button" class="modal-footer button btn-confirm" id="btn-confirm-pembayaran" disabled>
                    <i class="fas fa-check me-2"></i>Konfirmasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Pembayaran -->
<div class="modal-overlay" id="modal-konfirmasi">
    <div class="modal-content">
        <div class="modal-header">
            <i class="fas fa-info-circle me-2"></i>Konfirmasi Pembayaran
        </div>
        <div style="margin-bottom: 20px;">
            <p>Transaksi berhasil diproses!</p>
            <div class="summary-row" style="border: none; padding: 10px 0;">
                <span>Total Harga:</span>
                <span id="konfirmasi-total-harga"></span>
            </div>
            <div class="summary-row" style="border: none; padding: 10px 0;">
                <span>Uang Masuk:</span>
                <span id="konfirmasi-uang-masuk"></span>
            </div>
            <div class="summary-row" style="border: none; padding: 10px 0; color: #28a745;">
                <span>Kembalian:</span>
                <span id="konfirmasi-kembalian"></span>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-footer button btn-cancel" id="btn-tanpa-cetak">
                <i class="fas fa-times me-2"></i>Tanpa Cetak
            </button>
            <button type="button" class="modal-footer button btn-confirm" id="btn-cetak-nota">
                <i class="fas fa-print me-2"></i>Cetak Nota
            </button>
        </div>
    </div>
</div>

<!-- Hidden iframe untuk print -->
<iframe id="print-frame" style="display:none;"></iframe>

@endsection

@section('scripts')
<script>
    let rowCount = 1;
    let transactionData = [];

    // Format currency
    function formatCurrency(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(value);
    }

    // Search barang
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('input-barang')) {
            const rowIndex = e.target.dataset.rowIndex;
            const query = e.target.value.trim();
            const resultsDiv = document.querySelector(`.search-results[data-row-index="${rowIndex}"]`);

            if (query.length < 1) {
                resultsDiv.classList.remove('show');
                return;
            }

            fetch(`/kasir/search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        resultsDiv.innerHTML = '<div class="search-item" style="color: #999; cursor: default;">Barang tidak ditemukan</div>';
                    } else {
                        resultsDiv.innerHTML = data.map(item => `
                            <div class="search-item" data-id="${item.id}" data-harga="${item.harga_jual}" data-stok="${item.stok}">
                                <div class="search-item-name">${item.nama}</div>
                                <div class="search-item-code">Kode: ${item.kode_barang}</div>
                                <div class="search-item-price">${formatCurrency(item.harga_jual)} (Stok: ${item.stok})</div>
                            </div>
                        `).join('');
                    }
                    resultsDiv.classList.add('show');
                });
        }
    });

    // Select barang from search
    document.addEventListener('click', function(e) {
        if (e.target.closest('.search-item')) {
            const item = e.target.closest('.search-item');
            const rowIndex = item.closest('.search-results').dataset.rowIndex;
            const row = document.querySelector(`.item-row[data-row-index="${rowIndex}"]`);

            const id = item.dataset.id;
            const nama = item.querySelector('.search-item-name').textContent;
            const harga = item.dataset.harga;
            const stok = item.dataset.stok;

            row.querySelector('.input-barang').value = nama;
            row.querySelector('.item-id').value = id;
            row.querySelector('.harga-satuan').value = formatCurrency(harga);
            
            // Set max qty berdasarkan stok
            const qtyInput = row.querySelector('.input-qty');
            qtyInput.max = stok;
            qtyInput.dataset.stok = stok;
            
            // Store stok info di row untuk reference
            row.dataset.stok = stok;
            
            row.querySelector('.search-results').classList.remove('show');

            // Focus to qty field
            qtyInput.focus();
            
            calculateRow(rowIndex);
        }
    });

    // Close search results when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search-input')) {
            document.querySelectorAll('.search-results').forEach(el => {
                el.classList.remove('show');
            });
        }
    });

    // Calculate when qty changes
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('input-qty')) {
            const rowIndex = e.target.dataset.rowIndex;
            const row = document.querySelector(`.item-row[data-row-index="${rowIndex}"]`);
            const qtyInput = e.target;
            const stok = parseInt(row.dataset.stok) || 0;
            let qty = parseInt(qtyInput.value) || 0;
            
            // Validasi qty tidak melebihi stok
            if (qty > stok && stok > 0) {
                qtyInput.value = stok;
                qty = stok;
                // Show warning
                const errorDiv = row.querySelector('.qty-error');
                if (errorDiv) {
                    errorDiv.remove();
                }
                const warning = document.createElement('div');
                warning.className = 'qty-error';
                warning.style.color = '#dc3545';
                warning.style.fontSize = '0.85rem';
                warning.style.marginTop = '3px';
                warning.textContent = `Stok hanya ${stok} unit`;
                qtyInput.parentNode.appendChild(warning);
                
                // Remove warning after 3 seconds
                setTimeout(() => {
                    if (warning.parentNode) warning.remove();
                }, 3000);
            }
            
            calculateRow(rowIndex);
        }
    });

    function calculateRow(rowIndex) {
        const row = document.querySelector(`.item-row[data-row-index="${rowIndex}"]`);
        if (!row) return;

        const hargaText = row.querySelector('.harga-satuan').value;
        const qty = parseInt(row.querySelector('.input-qty').value) || 0;
        const itemId = row.querySelector('.item-id').value;
        const barangInput = row.querySelector('.input-barang').value;

        // Extract harga from formatted currency
        const harga = parseInt(hargaText.replace(/\D/g, '')) || 0;
        const subtotal = harga * qty;

        row.querySelector('.subtotal').value = subtotal > 0 ? formatCurrency(subtotal) : '';

        // Update row state
        if (barangInput && qty > 0 && harga > 0 && itemId) {
            row.classList.add('filled');
            row.classList.remove('empty');
            row.querySelector('.btn-delete').style.display = 'block';

            // Add new row if this is the last filled row
            if (rowIndex == rowCount - 1) {
                addNewRow();
            }
        } else {
            row.classList.remove('filled');
            row.classList.add('empty');
            if (rowIndex != 0) {
                row.querySelector('.btn-delete').style.display = 'block';
            }
        }

        updateSummary();
    }

    function addNewRow() {
        rowCount++;
        const container = document.getElementById('items-container');
        const newRow = document.createElement('div');
        newRow.className = 'item-row empty';
        newRow.dataset.rowIndex = rowCount - 1;
        newRow.innerHTML = `
            <div class="search-input">
                <input 
                    type="text" 
                    class="form-control input-barang" 
                    placeholder="Cari nama atau kode barang..."
                    data-row-index="${rowCount - 1}"
                >
                <div class="search-results" data-row-index="${rowCount - 1}"></div>
                <input type="hidden" class="item-id" value="">
            </div>
            <div>
                <input 
                    type="number" 
                    class="form-control input-qty" 
                    placeholder="Qty"
                    min="1"
                    data-row-index="${rowCount - 1}"
                >
            </div>
            <div>
                <input 
                    type="text" 
                    class="form-control harga-satuan" 
                    placeholder="Harga"
                    readonly
                >
            </div>
            <div>
                <input 
                    type="text" 
                    class="form-control subtotal" 
                    placeholder="Subtotal"
                    readonly
                >
            </div>
            <div>
                <button type="button" class="btn-delete" style="display:none;">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(newRow);
    }

    function updateSummary() {
        let totalQty = 0;
        let totalHarga = 0;
        let hasFilledItems = false;

        document.querySelectorAll('.item-row.filled').forEach(row => {
            const qty = parseInt(row.querySelector('.input-qty').value) || 0;
            const subtotalText = row.querySelector('.subtotal').value;
            const subtotal = parseInt(subtotalText.replace(/\D/g, '')) || 0;

            totalQty += qty;
            totalHarga += subtotal;

            if (qty > 0 && row.querySelector('.item-id').value) {
                hasFilledItems = true;
            }
        });

        document.getElementById('total-items').textContent = totalQty;
        document.getElementById('total-harga').textContent = formatCurrency(totalHarga);

        const btnTransaksi = document.getElementById('btn-transaksi');
        btnTransaksi.disabled = !hasFilledItems;
    }

    // Delete row
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-delete')) {
            const row = e.target.closest('.item-row');
            row.remove();
            updateSummary();
        }
    });

    // Open payment modal
    document.getElementById('btn-transaksi').addEventListener('click', function() {
        const totalHarga = parseInt(document.getElementById('total-harga').textContent.replace(/\D/g, ''));
        
        if (totalHarga <= 0) {
            alert('Tambahkan item terlebih dahulu!');
            return;
        }

        document.getElementById('display-total-harga').value = formatCurrency(totalHarga);
        document.getElementById('uang-masuk').value = '';
        document.getElementById('kembalian-display').value = '';
        document.getElementById('error-uang').textContent = '';
        document.getElementById('result-text').style.display = 'none';
        document.getElementById('btn-confirm-pembayaran').disabled = true;
        document.getElementById('modal-pembayaran').classList.add('show');
    });

    // Calculate kembalian
    document.getElementById('uang-masuk').addEventListener('input', function() {
        const totalHarga = parseInt(document.getElementById('total-harga').textContent.replace(/\D/g, ''));
        const uangMasuk = parseInt(this.value) || 0;
        const kembalian = uangMasuk - totalHarga;

        document.getElementById('error-uang').textContent = '';
        document.getElementById('result-text').style.display = 'none';
        document.getElementById('btn-confirm-pembayaran').disabled = true;

        if (uangMasuk > 0) {
            if (kembalian < 0) {
                document.getElementById('error-uang').textContent = `Uang kurang ${formatCurrency(Math.abs(kembalian))}`;
                document.getElementById('kembalian-display').value = '';
            } else {
                document.getElementById('kembalian-display').value = formatCurrency(kembalian);
                document.getElementById('result-text').textContent = `✓ Pembayaran dapat diproses!`;
                document.getElementById('result-text').style.display = 'block';
                document.getElementById('btn-confirm-pembayaran').disabled = false;
            }
        }
    });

    // Cancel pembayaran
    document.getElementById('btn-cancel-pembayaran').addEventListener('click', function() {
        document.getElementById('modal-pembayaran').classList.remove('show');
    });

    // Confirm pembayaran
    document.getElementById('btn-confirm-pembayaran').addEventListener('click', function() {
        const totalHarga = parseInt(document.getElementById('total-harga').textContent.replace(/\D/g, ''));
        const uangMasuk = parseInt(document.getElementById('uang-masuk').value) || 0;
        const kembalian = uangMasuk - totalHarga;

        if (uangMasuk < totalHarga) {
            alert('Uang tidak cukup!');
            return;
        }

        // Collect items data
        const items = [];
        document.querySelectorAll('.item-row.filled').forEach(row => {
            const itemId = row.querySelector('.item-id').value;
            const qty = parseInt(row.querySelector('.input-qty').value);
            const hargaSatuanText = row.querySelector('.harga-satuan').value;
            const hargaSatuan = parseInt(hargaSatuanText.replace(/\D/g, ''));
            const subtotalText = row.querySelector('.subtotal').value;
            const subtotal = parseInt(subtotalText.replace(/\D/g, ''));

            if (itemId && qty > 0 && hargaSatuan > 0) {
                items.push({
                    id_barang: parseInt(itemId),
                    qty: qty,
                    harga_satuan: hargaSatuan,
                    subtotal: subtotal
                });
            }
        });

        if (items.length === 0) {
            alert('Tidak ada item yang valid!');
            return;
        }

        console.log('Items to send:', items);
        console.log('Total data:', {
            items: items,
            total_harga: totalHarga,
            total_bayar: uangMasuk,
            kembalian: kembalian
        });

        // Disable button saat process
        const btnConfirm = document.getElementById('btn-confirm-pembayaran');
        const originalText = btnConfirm.innerHTML;
        btnConfirm.disabled = true;
        btnConfirm.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';

        // Send to server
        fetch('/kasir/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify({
                items: items,
                total_harga: totalHarga,
                total_bayar: uangMasuk,
                kembalian: kembalian
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Network response was not ok');
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                document.getElementById('modal-pembayaran').classList.remove('show');

                // Show confirmation modal
                document.getElementById('konfirmasi-total-harga').textContent = formatCurrency(totalHarga);
                document.getElementById('konfirmasi-uang-masuk').textContent = formatCurrency(uangMasuk);
                document.getElementById('konfirmasi-kembalian').textContent = formatCurrency(kembalian);
                document.getElementById('modal-konfirmasi').classList.add('show');
            } else {
                alert('Terjadi kesalahan: ' + (data.message || 'Transaksi gagal'));
                console.error('Server error response:', data);
                btnConfirm.disabled = false;
                btnConfirm.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Terjadi kesalahan saat memproses transaksi: ' + error.message);
            btnConfirm.disabled = false;
            btnConfirm.innerHTML = originalText;
        });
    });

    // Selesai transaksi tanpa cetak
    document.getElementById('btn-tanpa-cetak').addEventListener('click', function() {
        selesaiTransaksi();
    });

    // Cetak nota
    document.getElementById('btn-cetak-nota').addEventListener('click', function() {
        cetakNota();
        // Selesai transaksi setelah cetak
        setTimeout(() => {
            selesaiTransaksi();
        }, 500);
    });

    // Function untuk cetak nota
    function cetakNota() {
        const totalHarga = document.getElementById('konfirmasi-total-harga').textContent;
        const uangMasuk = document.getElementById('konfirmasi-uang-masuk').textContent;
        const kembalian = document.getElementById('konfirmasi-kembalian').textContent;

        // Ambil items dari form
        const items = [];
        document.querySelectorAll('.item-row.filled').forEach(row => {
            const nama = row.querySelector('.input-barang').value;
            const qty = row.querySelector('.input-qty').value;
            const harga = row.querySelector('.harga-satuan').value;
            const subtotal = row.querySelector('.subtotal').value;

            items.push({
                nama: nama,
                qty: qty,
                harga: harga,
                subtotal: subtotal
            });
        });

        // Generate HTML nota
        const notaHTML = `
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>nota</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            width: 80mm;
            margin: 0 auto;
            padding: 10mm;
            background: white;
        }
        
        .nota-container {
            text-align: center;
            border: 1px dashed #000;
            padding: 15px;
        }
        
        .header {
            margin-bottom: 15px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            letter-spacing: 2px;
        }
        
        .subtitle {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .greeting {
            font-size: 10px;
            font-weight: bold;
            margin: 15px 0;
            line-height: 1.5;
            text-align: center;
        }
        
        .items {
            text-align: left;
            margin: 15px 0;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 10px 0;
            font-size: 12px;
        }
        
        .item-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            align-items: flex-start;
        }
        
        .item-name {
            flex: 1;
            font-weight: bold;
        }
        
        .item-qty {
            width: 50px;
            text-align: center;
        }
        
        .item-price {
            width: 70px;
            text-align: right;
        }
        
        .summary {
            margin: 15px 0;
            font-size: 12px;
            text-align: right;
        }
        
        .summary-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 5px;
            gap: 20px;
        }
        
        .summary-row.total {
            border-top: 1px solid #000;
            padding-top: 8px;
            font-weight: bold;
            font-size: 14px;
        }
        
        .summary-row.kembalian {
            border-top: 1px dashed #000;
            padding-top: 8px;
            font-weight: bold;
            color: #00AA00;
        }
        
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 11px;
            line-height: 1.6;
            border-top: 1px solid #000;
            padding-top: 10px;
        }
        
        .thank-you {
            font-size: 13px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .datetime {
            font-size: 11px;
            margin-top: 10px;
            color: #666;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .nota-container {
                border: none;
            }
        }
    </style>
</head>
<body>
    <div class="nota-container">
        <div class="header">
            <div class="logo">SIPTA</div>
            <div class="subtitle">Jl.KH Wachid Hasyim No.94, Bandar Lor, Kecamatan Mojoroto, Kota Kediri</div>
        </div>
        
        <div class="greeting">
            ✓ TERIMA KASIH<br>
            atas pembelian Anda!<br>
            <br>
            Harap belanja lagi di sini<br>
            dan nikmati penawaran menarik<br>
            lainnya dari kami.
        </div>
        
        <div class="items">
            ${items.map((item, index) => `
            <div class="item-row">
                <div class="item-name">${item.nama}</div>
            </div>
            <div class="item-row">
                <div style="flex: 1; padding-left: 10px; font-size: 11px; color: #666;">
                    ${item.qty} x ${item.harga} = ${item.subtotal}
                </div>
            </div>
            `).join('')}
        </div>
        
        <div class="summary">
            <div class="summary-row">
                <span>Total Harga :</span>
                <span>${totalHarga}</span>
            </div>
            <div class="summary-row">
                <span>Uang Masuk :</span>
                <span>${uangMasuk}</span>
            </div>
            <div class="summary-row kembalian">
                <span>Kembalian :</span>
                <span>${kembalian}</span>
            </div>
        </div>
        
        <div class="footer">
            <div class="thank-you">TERIMA KASIH! 🙏</div>
            <div style="margin: 10px 0; line-height: 1.5; font-size: 10px;">
                Hubungi kami untuk pertanyaan<br>
                atau keluhan produk
            </div>
            <div class="datetime">
                ${new Date().toLocaleString('id-ID', { 
                    year: 'numeric', 
                    month: '2-digit', 
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                })}
            </div>
            <div style="margin-top: 10px; font-size: 10px; letter-spacing: 1px;">
                ════════════════════════
            </div>
        </div>
    </div>
</body>
</html>
        `;

        // Buka di iframe
        const printFrame = document.getElementById('print-frame');
        printFrame.contentDocument.open();
        printFrame.contentDocument.write(notaHTML);
        printFrame.contentDocument.close();

        // Print setelah iframe siap
        setTimeout(() => {
            printFrame.contentWindow.print();
        }, 250);
    }

    // Function untuk selesai transaksi
    function selesaiTransaksi() {
        document.getElementById('modal-konfirmasi').classList.remove('show');
        
        // Reset form
        document.getElementById('items-container').innerHTML = `
            <div class="item-row empty" data-row-index="0">
                <div class="search-input">
                    <input 
                        type="text" 
                        class="form-control input-barang" 
                        placeholder="Cari nama atau kode barang..."
                        data-row-index="0"
                    >
                    <div class="search-results" data-row-index="0"></div>
                    <input type="hidden" class="item-id" value="">
                </div>
                <div>
                    <input 
                        type="number" 
                        class="form-control input-qty" 
                        placeholder="Qty"
                        min="1"
                        data-row-index="0"
                    >
                </div>
                <div>
                    <input 
                        type="text" 
                        class="form-control harga-satuan" 
                        placeholder="Harga"
                        readonly
                    >
                </div>
                <div>
                    <input 
                        type="text" 
                        class="form-control subtotal" 
                        placeholder="Subtotal"
                        readonly
                    >
                </div>
                <div>
                    <button type="button" class="btn-delete" style="display:none;">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        rowCount = 1;
        updateSummary();
    }

    // Go to history
    document.getElementById('btn-riwayat').addEventListener('click', function() {
        window.location.href = '/kasir/history';
    });

    // Close modals on overlay click
    document.addEventListener('click', function(e) {
        if (e.target.id === 'modal-pembayaran') {
            document.getElementById('modal-pembayaran').classList.remove('show');
        }
    });

    // Get CSRF token from meta tag
    function getCsrfToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            return token.getAttribute('content');
        }
        // Fallback: create from DOM
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = '{{ csrf_token() }}';
        document.head.appendChild(meta);
        return meta.getAttribute('content');
    }

    // Initialize
    updateSummary();
</script>
@endsection
