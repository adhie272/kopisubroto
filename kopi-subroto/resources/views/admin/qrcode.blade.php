@extends('admin.layouts.app')

@section('title', 'QR Code Menu')
@section('page-title', 'QR Code Menu')
@section('breadcrumb', 'Admin / QR Code Menu')

@push('styles')
<style>
    .qr-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 28px;
        text-align: center;
        transition: all 0.3s;
        position: relative;
    }

    .qr-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    .qr-card canvas {
        border-radius: 12px;
        border: 4px solid #f8fafc;
    }

    @media print {
        .admin-sidebar, .admin-topbar, .no-print { display: none !important; }
        .admin-main { margin-left: 0 !important; }
        .admin-content { padding: 0 !important; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .qr-card {
            page-break-inside: avoid;
            border: none !important;
            box-shadow: none !important;
            padding: 0;
        }
        .qr-card:hover { transform: none; }
    }
</style>
@endpush

@section('content')
<div class="content-card mb-6 no-print">
    <div class="card-header">
        <h3>Konfigurasi QR Code</h3>
    </div>
    <div class="card-body padded">
        <div class="flex flex-wrap items-end gap-4">
            <div>
                <label style="font-size:0.75rem; font-weight:600; color:#475569; display:block; margin-bottom:6px;">Base URL</label>
                <input type="text" id="baseUrl" value="{{ $appUrl }}"
                       class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm w-72 focus:ring-2 focus:ring-brand-teal">
            </div>
            <button onclick="generateQR()" class="btn btn-primary">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Generate QR
            </button>
            <button onclick="window.print()" class="btn btn-amber">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print QR
            </button>
        </div>
    </div>
</div>

<div class="flex justify-center">
    <div class="qr-card animate-in">
        <div id="singleQr" style="display:inline-block;"></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
function generateQR() {
    const baseUrl = document.getElementById('baseUrl').value.replace(/\/$/, '');
    const qrContainer = document.getElementById('singleQr');
    qrContainer.innerHTML = '';

    new QRCode(qrContainer, {
        text: baseUrl,
        width: 220,
        height: 220,
        colorDark: getComputedStyle(document.documentElement).getPropertyValue('--brand-deep').trim(),
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.H,
    });
}

document.addEventListener('DOMContentLoaded', generateQR);
</script>
@endpush
