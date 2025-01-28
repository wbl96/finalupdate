@extends('layouts.store.app')

@section('title', trans('users.store'))

@php
    // تحديد اسم الحقل حسب اللغة
    $name = app()->getLocale() == 'ar' ? 'name_ar' : 'name_en';
@endphp

@section('css')
<style>
    .rfq-section {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 30px 0;
    }

    .rfq-header {
        background: linear-gradient(135deg, #6c927f 0%, #2c5282 100%);
        border-radius: 20px;
        padding: 40px;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 10px 20px rgba(108, 146, 127, 0.2);
    }

    .rfq-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='rgba(255,255,255,0.05)' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.1;
    }

    .rfq-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    .table {
        border-collapse: separate;
        border-spacing: 0 15px;
    }

    .header-row {
        background: linear-gradient(135deg, #6c927f 0%, #2c5282 100%);
    }

    .table thead th {
        color: white;
        font-weight: 500;
        padding: 15px;
        border: none;
        font-size: 0.95rem;
        white-space: nowrap;
        vertical-align: middle;
        text-align: center;
        background: transparent;
    }

    .table thead th:first-child {
        border-radius: 0 15px 15px 0;
        padding-right: 25px;
    }

    .table thead th:last-child {
        border-radius: 15px 0 0 15px;
        padding-left: 25px;
    }

    .table tbody td {
        border: none;
        background-color: white;
        padding: 15px;
        position: relative;
    }

    .table tbody tr {
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .table tbody tr td:first-child {
        border-radius: 0 15px 15px 0;
        padding-right: 25px;
    }

    .table tbody tr td:last-child {
        border-radius: 15px 0 0 15px;
        padding-left: 25px;
    }

    .table tbody tr:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .fs-smaller {
        font-size: 0.8rem;
        opacity: 0.9;
    }

    .product-image {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        object-fit: cover;
    }

    .form-check-input {
        width: 20px;
        height: 20px;
        border-color: #6c927f;
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: #6c927f;
        border-color: #6c927f;
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(108, 146, 127, 0.25);
    }

    .summary-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .summary-title {
        color: #2c5282;
        font-size: 1.25rem;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .summary-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .bank-info-card {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 25px;
        margin-top: 20px;
    }

    .bank-info-title {
        color: #2c5282;
        font-size: 1.1rem;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .bank-info-item {
        margin-bottom: 15px;
    }

    .bank-info-item p {
        color: #666;
        margin-bottom: 5px;
    }

    .bank-info-item h6 {
        color: #2c5282;
        margin: 0;
    }

    .btn-confirm {
        background: linear-gradient(135deg, #6c927f 0%, #4a7862 100%);
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(108, 146, 127, 0.3);
    }

    .file-upload {
        background: #f8f9fa;
        border: 2px dashed #6c927f;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        margin: 20px 0;
        transition: all 0.3s ease;
    }

    .file-upload:hover {
        background: #fff;
        border-color: #2c5282;
    }

    .file-error {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 5px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 4rem;
        color: #ccc;
        margin-bottom: 20px;
    }

    .product-row {
        transition: all 0.3s ease;
    }

    .product-row:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
    }

    .product-image-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-image-wrapper:hover .product-image {
        transform: scale(1.1);
    }

    .product-name {
        font-weight: 600;
        color: #2c5282;
        margin-bottom: 5px;
    }

    .product-detail {
        font-size: 0.9rem;
        color: #666;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .product-detail strong {
        color: #4a7862;
    }

    .product-price {
        font-weight: 600;
        color: #2c5282;
    }

    .product-total {
        font-weight: bold;
        color: #4a7862;
        font-size: 1.1rem;
    }

    .product-currency {
        font-size: 0.85rem;
        color: #666;
    }

    .product-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.85rem;
        margin-right: 8px;
    }

    .badge-deleted {
        background-color: #fee2e2;
        color: #dc2626;
    }

    .table thead .form-check-input {
        width: 20px;
        height: 20px;
        border-color: white;
        background-color: transparent;
    }

    .table thead .form-check-input:checked {
        background-color: white;
        border-color: white;
    }

    .product-table {
        border-spacing: 0 15px !important;
        border-collapse: separate !important;
        margin-top: -15px;
    }

    .product-row {
        background: white;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        border-radius: 15px;
        transition: all 0.3s ease;
    }

    .product-row:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .product-row td {
        border: none !important;
    }

    .product-row td:first-child {
        border-radius: 15px 0 0 15px;
    }

    .product-row td:last-child {
        border-radius: 0 15px 15px 0;
    }

    .product-image-wrapper {
        width: 80px;
        height: 80px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        position: relative;
    }

    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .product-image-wrapper:hover .product-image {
        transform: scale(1.15);
    }

    .product-image-wrapper .no-image {
        position: absolute;
        inset: 0;
        background: linear-gradient(45deg, #f3f4f6, #e5e7eb);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-content {
        padding-right: 15px;
    }

    .product-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c5282;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .product-detail {
        background: #f8f9fa;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.9rem;
        color: #4b5563;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .product-detail strong {
        color: #4a7862;
        font-weight: 600;
    }

    .product-quantity {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 8px 15px;
        border-radius: 10px;
        font-weight: 600;
        display: inline-block;
    }

    .product-price-wrapper {
        text-align: center;
    }

    .product-price {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c5282;
        margin-bottom: 4px;
    }

    .product-currency {
        font-size: 0.85rem;
        color: #6b7280;
        display: block;
    }

    .product-total-wrapper {
        background: #f0f9ff;
        padding: 10px 15px;
        border-radius: 12px;
        text-align: center;
    }

    .product-total {
        font-size: 1.2rem;
        font-weight: bold;
        color: #1e40af;
        margin-bottom: 4px;
    }

    .product-badge {
        background: #fee2e2;
        color: #dc2626;
        padding: 5px 10px;
        border-radius: 8px;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .form-check-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        padding: 0 10px;
    }

    .form-check-input {
        width: 20px;
        height: 20px;
        margin: 0;
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: #6c927f;
        border-color: #6c927f;
    }

    .table thead .form-check-input {
        border-color: white;
        background-color: transparent;
    }

    .table thead .form-check-input:checked {
        background-color: white;
        border-color: white;
    }

    .table tbody .form-check-input {
        border: 2px solid #6c927f;
        border-radius: 6px;
    }

    .table tbody .form-check-input:checked {
        background-color: #6c927f;
        border-color: #6c927f;
    }

    .product-table {
        border-spacing: 0 15px !important;
        border-collapse: separate !important;
        margin-top: -15px;
    }

    .product-row {
        background: white;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        border-radius: 15px;
        transition: all 0.3s ease;
    }

    .product-row:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .product-row td {
        border: none !important;
    }

    .product-row td:first-child {
        border-radius: 15px 0 0 15px;
    }

    .product-row td:last-child {
        border-radius: 0 15px 15px 0;
    }

    .product-image-wrapper {
        width: 80px;
        height: 80px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        position: relative;
    }

    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .product-image-wrapper:hover .product-image {
        transform: scale(1.15);
    }

    .product-image-wrapper .no-image {
        position: absolute;
        inset: 0;
        background: linear-gradient(45deg, #f3f4f6, #e5e7eb);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-content {
        padding-right: 15px;
    }

    .product-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c5282;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .product-detail {
        background: #f8f9fa;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.9rem;
        color: #4b5563;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .product-detail strong {
        color: #4a7862;
        font-weight: 600;
    }

    .product-quantity {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 8px 15px;
        border-radius: 10px;
        font-weight: 600;
        display: inline-block;
    }

    .product-price-wrapper {
        text-align: center;
    }

    .product-price {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c5282;
        margin-bottom: 4px;
    }

    .product-currency {
        font-size: 0.85rem;
        color: #6b7280;
        display: block;
    }

    .product-total-wrapper {
        background: #f0f9ff;
        padding: 10px 15px;
        border-radius: 12px;
        text-align: center;
    }

    .product-total {
        font-size: 1.2rem;
        font-weight: bold;
        color: #1e40af;
        margin-bottom: 4px;
    }

    .product-badge {
        background: #fee2e2;
        color: #dc2626;
        padding: 5px 10px;
        border-radius: 8px;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .order-sidebar {
        position: sticky;
        top: 20px;
    }

    .sidebar-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .sidebar-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }

    .card-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #6c927f20 0%, #2c528220 100%);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }

    .card-icon i {
        font-size: 24px;
        color: #6c927f;
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c5282;
        margin-bottom: 20px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px dashed rgba(0,0,0,0.08);
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #64748b;
        font-size: 0.95rem;
    }

    .info-value {
        font-weight: 600;
        color: #2c5282;
    }

    .total .info-value {
        font-size: 1.2rem;
        color: #6c927f;
    }

    .currency {
        font-size: 0.85rem;
        color: #64748b;
        margin-right: 5px;
    }

    .upload-area {
        border: 2px dashed #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .upload-area:hover {
        border-color: #6c927f;
        background: #f8fafc;
    }

    .upload-message {
        color: #64748b;
        margin-top: 10px;
        font-size: 0.9rem;
    }

    .file-error {
        color: #dc2626;
        margin-top: 8px;
        font-size: 0.9rem;
    }

    .submit-button {
        width: 100%;
        background: linear-gradient(135deg, #6c927f 0%, #2c5282 100%);
        color: white;
        border: none;
        border-radius: 15px;
        padding: 15px 25px;
        font-size: 1.1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .submit-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(108, 146, 127, 0.3);
    }

    .submit-button i {
        font-size: 20px;
    }

    .order-details {
        margin-top: 30px;
    }

    .sidebar-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    @media (max-width: 768px) {
        /* تغليف الجدول */
        .rfq-container {
            width: 100%;
            overflow-x: scroll; /* تفعيل التمرير الأفقي */
            -webkit-overflow-scrolling: touch;
            padding: 0;
            margin: 0;
        }

        /* تنسيق الجدول نفسه */
        .table.product-table {
            width: 800px; /* عرض ثابت */
            min-width: 800px;
            margin: 0;
        }

        /* تأكيد عدم التفاف المحتوى */
        .table.product-table th,
        .table.product-table td {
            white-space: nowrap;
            min-width: auto;
        }

        /* تحديد عرض الأعمدة */
        .table.product-table th:first-child,
        .table.product-table td:first-child {
            width: 50px;
            min-width: 50px;
        }

        .table.product-table th:nth-child(2),
        .table.product-table td:nth-child(2) {
            width: 100px;
            min-width: 100px;
        }

        .table.product-table th:nth-child(3),
        .table.product-table td:nth-child(3) {
            width: 250px;
            min-width: 250px;
        }

        .table.product-table th:nth-child(4),
        .table.product-table td:nth-child(4) {
            width: 120px;
            min-width: 120px;
        }

        .table.product-table th:nth-child(5),
        .table.product-table td:nth-child(5) {
            width: 140px;
            min-width: 140px;
        }

        .table.product-table th:last-child,
        .table.product-table td:last-child {
            width: 140px;
            min-width: 140px;
        }
    }

    @media (max-width: 480px) {
        .rfq-header {
            margin: 5px 5px 0 5px;
        }

        .table-responsive {
            margin-top: 5px;
        }

        .table thead th,
        .table tbody td {
            padding: 8px;
            font-size: 13px;
        }

        .product-image-wrapper {
            width: 40px;
            height: 40px;
        }

        .product-name {
            font-size: 13px;
        }

        .product-detail {
            font-size: 12px;
        }

        .card-content {
            padding: 10px;
        }

        .info-label,
        .info-value {
            font-size: 13px;
        }
    }
</style>
@endsection

@section('content')
<div class="rfq-section">
    <div class="container">
        <!-- RFQ Header -->
        <div class="rfq-header">
            <h4 class="mb-3">طلبات عروض الأسعار</h4>
            <p class="mb-0 opacity-75">قم باختيار العروض المناسبة وأكمل عملية الطلب</p>
        </div>

        @if (isset($rfqRequests) && count($rfqRequests))
            <form action="{{ route('store.rfq.store') }}" method="post" id="form" enctype="multipart/form-data">
                @csrf
                <!-- جدول المنتجات -->
                <div class="rfq-container mb-4">
                    <table class="table product-table">
                        <thead>
                            <tr class="header-row">
                                <th class="text-center" style="width: 50px">
                                    <input type="checkbox" class="form-check-input" id="check_all">
                                </th>
                                <th style="width: 100px">{{ __('products.image') }}</th>
                                <th style="width: 35%">{{ __('products.name.name') }}</th>
                                <th class="text-center" style="width: 120px">{{ __('orders.Quantity') }}</th>
                                <th class="text-center" style="width: 150px">{{ __('orders.Price per piece') }}</th>
                                <th class="text-center" style="width: 180px">
                                    <div>{!! __('orders.Total Amount With VAT') !!}</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rfqRequests as $k => $item)
                                @php
                                    $product = $item->detailKey->detail->product;
                                    $name = config('app.locale') == 'ar' ? 'name_ar' : 'name_en';
                                @endphp
                                <tr class="product-row">
                                    <td>
                                        <div class="form-check-wrapper">
                                            <input type="checkbox" class="form-check-input item-input"
                                                name="detail_key[{{ $k }}]"
                                                data-id="{{ $item->detail_key_id }}"
                                                value="{{ $item->detail_key_id }}">
                                            <input type="hidden" name="id[{{ $k }}]"
                                                value="{{ $item->id }}">
                                            <input type="hidden" name="supplier_id[{{ $k }}]"
                                                value="{{ $item->supplier_id }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="product-image-wrapper">
                                            @if ($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" 
                                                     class="product-image" 
                                                     alt="{{ $product->name_ar }}">
                                            @else
                                                <div class="no-image">
                                                    <i class="bi bi-image text-muted fs-3"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="product-content">
                                            <div class="product-name">
                                                {{ $product->$name }}
                                                @if ($product->trashed())
                                                    <span class="product-badge">
                                                        <i class="bi bi-trash"></i>
                                                        تم الحذف مؤقتاً
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="product-detail">
                                                <i class="bi bi-tag"></i>
                                                {{ $item->detailKey->detail->name }}: 
                                                <strong>{{ $item->detailKey->key }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="product-quantity">
                                            {{ $item->qty }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="product-price-wrapper">
                                            <div class="product-price" id="price_{{ $item->detail_key_id }}">
                                                {{ number_format($item->proposed_price, 2) }}
                                            </div>
                                            <span class="product-currency">{{ trans('global.SAR') }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="product-total-wrapper">
                                            <div class="product-total">
                                                {{ number_format($item->proposed_price * $item->qty, 2) }}
                                            </div>
                                            <span class="product-currency">{{ trans('global.SAR') }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- تفاصيل الطلب -->
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="order-details">
                            <!-- ملخص الطلب -->
                            <div class="sidebar-card">
                                <div class="card-icon">
                                    <i class="bi bi-cart-check"></i>
                                </div>
                                <h5 class="card-title">تفاصيل المنتجات المختارة</h5>
                                <div class="card-content">
                                    <div class="info-row">
                                        <div class="info-label">{{ __('orders.Quantity') }}</div>
                                        <div class="info-value" id="tot_qty">0</div>
                                    </div>
                                    <div class="info-row total">
                                        <div class="info-label">{{ __('orders.Total Amount With VAT') }}</div>
                                        <div class="info-value">
                                            <input type="hidden" id="total_price" name="total_price" value="0.00">
                                            <span id="tot_price">0.00</span>
                                            <small class="currency">{{ trans('global.SAR') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- خيارات الدفع -->
                            <div class="sidebar-card">
                                <div class="card-icon">
                                    <i class="bi bi-wallet2"></i>
                                </div>
                                <h5 class="card-title">طريقة الدفع</h5>
                                <div class="card-content">
                                    @if(auth()->user()->wallet && auth()->user()->wallet->balance > 0)
                                        @if(!auth()->user()->wallet->transactions()->unpaid()->exists())
                                            <div class="payment-method mb-4">
                                                <div class="form-check">
                                                    <input type="checkbox" 
                                                           class="form-check-input" 
                                                           name="use_wallet" 
                                                           id="use_wallet"
                                                           value="1">
                                                    <label class="form-check-label" for="use_wallet">
                                                        استخدام رصيد المحفظة (دفع آجل)
                                                    </label>
                                                </div>
                                                <div class="wallet-info mt-3">
                                                    <div class="alert alert-info">
                                                        <div class="mb-2">
                                                            <strong>الرصيد المتاح:</strong> 
                                                            <span id="available_balance">{{ number_format(auth()->user()->wallet->balance, 2) }}</span> ر.س
                                                        </div>
                                                        <div class="mb-2">
                                                            <strong>المبلغ المراد استخدامه من المحفظة:</strong>
                                                            <input type="number" 
                                                                   name="wallet_amount" 
                                                                   id="wallet_amount" 
                                                                   class="form-control mt-1"
                                                                   max="{{ auth()->user()->wallet->balance }}"
                                                                   step="0.01"
                                                                   disabled>
                                                        </div>
                                                        <div class="mb-2">
                                                            <strong>المبلغ المتبقي (تحويل بنكي):</strong>
                                                            <span id="remaining_amount">{{ number_format($totalAmount, 2) }}</span> ر.س
                                                        </div>
                                                        <div class="credit-terms">
                                                            <i class="bi bi-info-circle"></i>
                                                            شروط الدفع الآجل:
                                                            <ul class="mb-0 mt-1">
                                                                <li>فترة السداد: 40 يوم من تاريخ الطلب</li>
                                                                <li>تاريخ الاستحقاق: {{ now()->addDays(40)->format('Y-m-d') }}</li>
                                                                <li>لن يمكن استخدام رصيد المحفظة مجدداً حتى سداد هذا المبلغ</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="bi bi-exclamation-triangle"></i>
                                                لا يمكن استخدام رصيد المحفظة حالياً. يوجد مبلغ سابق غير مسدد.
                                            </div>
                                        @endif
                                    @endif

                                    <!-- تفاصيل التحويل البنكي -->
                                    <div id="bank_section">
                                        <div class="bank-info">
                                            <div class="info-row">
                                                <div class="info-label">المستفيد</div>
                                                <div class="info-value">مؤسسة وبل للتجارة</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="info-label">رقم الحساب</div>
                                                <div class="info-value">60000000000000</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="info-label">البنك</div>
                                                <div class="info-value">مصرف الانماء</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="info-label">IBAN</div>
                                                <div class="info-value">SA000000000000000000000</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="info-label">رقم التوجيه</div>
                                                <div class="info-value">AAABBBCCC</div>
                                            </div>
                                        </div>
                                        <div class="upload-area mt-3">
                                            <input type="file" 
                                                   name="transfer_receipt" 
                                                   id="transfer_receipt" 
                                                   class="form-control"
                                                   accept="image/*,application/pdf">
                                            <div class="upload-message">اسحب الملف هنا أو اضغط للاختيار</div>
                                            <div class="file-error d-none">فضلا ارفق ايصال التحويل</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="submit-button">
                                <span>{{ trans('orders.confirm') }}</span>
                                <i class="bi bi-arrow-left"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        @else
            <div class="rfq-container">
                <div class="empty-state">
                    <i class="bi bi-clipboard-x"></i>
                    <h5>لا توجد طلبات عروض اسعار</h5>
                    <p class="text-muted">لم يتم العثور على أي طلبات عروض أسعار حالياً</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('script')
<script>
    $('#check_all').on('change', function(e) {
        $("input[type='checkbox']").prop("checked", $(this).is(":checked"));
        if (!$(this).is(":checked")) {
            $('#tot_qty').html('0')
            $('#tot_price').html('0.00')
            $('#total_price').val('0.00')
        } else {
            buildTotal()
        }
    })

    $('input.item-input').change(function(e) {
        buildTotal()
    })

    function buildTotal() {
        let tot_qty = 0
        let tot_price = 0
        
        $('input.item-input:checked').each(function() {
            const id = $(this).data('id')
            const row = $(this).closest('tr')
            // الحصول على الكمية والسعر من الصف مباشرة
            const qty = parseInt(row.find('td:eq(3)').text()) || 0
            const totalPrice = parseFloat(row.find('td:last').text().replace(/[^\d.-]/g, '')) || 0
            
            tot_qty += qty
            tot_price += totalPrice // السعر الإجمالي يشمل الضريبة بالفعل
        })
        
        // تحديث الكمية
        $('#tot_qty').html(tot_qty)
        
        // تنسيق السعر الإجمالي
        const formattedPrice = tot_price.toFixed(2)
        $('#total_price').val(formattedPrice)
        $('#tot_price').html(new Intl.NumberFormat('en-US').format(tot_price))
    }

    // إضافة التحكم في عرض/إخفاء عناصر الدفع
    $('input[name="use_wallet"]').change(function() {
        const isChecked = $(this).is(':checked');
        $('#wallet_amount').prop('disabled', !isChecked);
        
        if (!isChecked) {
            $('#wallet_amount').val('');
            updateRemainingAmount(0);
        }
    });

    $('#wallet_amount').on('input', function() {
        const walletAmount = parseFloat($(this).val()) || 0;
        const maxBalance = parseFloat($(this).attr('max'));
        const totalAmount = parseFloat($('#total_price').val());
        
        // التحقق من أن المبلغ لا يتجاوز الرصيد المتاح
        if (walletAmount > maxBalance) {
            $(this).val(maxBalance);
            updateRemainingAmount(maxBalance);
            return;
        }
        
        // التحقق من أن المبلغ لا يتجاوز إجمالي الطلب
        if (walletAmount > totalAmount) {
            $(this).val(totalAmount);
            updateRemainingAmount(totalAmount);
            return;
        }
        
        updateRemainingAmount(walletAmount);
    });

    function updateRemainingAmount(walletAmount) {
        const totalAmount = parseFloat($('#total_price').val());
        const remainingAmount = totalAmount - walletAmount;
        $('#remaining_amount').text(remainingAmount.toFixed(2));
    }

    $('#form').submit(function(e) {
        e.preventDefault();
        
        if (!$('input.item-input:checked').length) {
            $('input.item-input').closest('td').addClass('is-invalid bg-danger');
            alert("فضلا قم بإختيار عروض الاسعار الموافق عليها");
            return false;
        }

        // التحقق من الإيصال دائماً لأن هناك جزء تحويل بنكي
        if ($('#transfer_receipt')[0].files.length === 0) {
            $('.file-error').removeClass('d-none');
            $('#transfer_receipt').addClass('is-invalid');
            $('.upload-area').addClass('border-danger');
            alert('فضلا قم بإرفاق ايصال التحويل');
            return false;
        }

        $(this)[0].submit();
    });
</script>
@endpush
