@extends('layouts.store.app')

@section('title', trans('users.store'))
@section('css')
    <style>
        /* تحديث الألوان لتكون أكثر تناسقاً */
        :root {
            --primary-color: #2e7d32;      /* أخضر داكن */
            --primary-dark: #1b5e20;       /* أخضر أغمق */
            --primary-light: #4caf50;      /* أخضر فاتح */
            --secondary-color: #1565c0;    /* أزرق داكن */
            --danger-color: #dc3545;
            --success-color: #28a745;
            --primary-gradient: linear-gradient(135deg, #6c927f 0%, #2c5282 100%);
            --primary-shadow: rgba(108, 146, 127, 0.2);
        }

        .rfq-section {
            background: #f8f9fa;
            min-height: 100vh;
            padding: 30px 0;
        }

        .rfq-header {
            background: var(--primary-gradient);
            border-radius: 20px;
            padding: 40px;
            color: white;
            position: relative;
            overflow: hidden;
            margin-bottom: 30px;
            box-shadow: 0 10px 20px var(--primary-shadow);
        }

        .rfq-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5z' fill='rgba(255,255,255,0.05)' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.1;
        }

        .rfq-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .product-table {
            border-spacing: 0 15px !important;
            border-collapse: separate !important;
            margin-top: -15px;
        }

        .header-row {
            background: var(--primary-gradient) !important;
            box-shadow: 0 4px 15px var(--primary-shadow);
        }

        .table thead th {
            color: white !important;
            font-weight: 600 !important;
            font-size: 0.95rem;
            padding: 15px;
            border: none;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
            background: transparent !important;
        }

        .table thead th:first-child {
            border-radius: 0 15px 15px 0;
            padding-right: 25px;
        }

        .table thead th:last-child {
            border-radius: 15px 0 0 15px;
            padding-left: 25px;
        }

        .product-row {
            background: white;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08) !important;
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .product-row:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 25px rgba(0,0,0,0.12) !important;
        }

        .counter-control {
            display: flex;
            align-items: center;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 5px;
            width: fit-content;
            margin: 0 auto;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .counter-control .ah-btn-control {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none !important;
            background: white !important;
            color: var(--primary-color) !important;
            border-radius: 8px;
            margin: 0 2px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .counter-control .ah-btn-control:hover {
            background: var(--primary-color) !important;
            color: white !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(108, 146, 127, 0.2);
        }

        .counter-control .ah-btn-control:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(108, 146, 127, 0.1);
        }

        .counter-control .ah-input-control {
            width: 50px;
            height: 32px;
            text-align: center;
            border: none;
            background: white;
            font-weight: 600;
            color: var(--secondary-color);
            border-radius: 8px;
            margin: 0 4px;
            padding: 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .counter-control .ah-input-control:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(108, 146, 127, 0.2);
        }

        /* إخفاء أسهم input number */
        .counter-control .ah-input-control::-webkit-outer-spin-button,
        .counter-control .ah-input-control::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .counter-control .ah-input-control[type=number] {
            -moz-appearance: textfield;
        }

        /* تحسين حجم الأيقونات */
        .counter-control .bi-plus,
        .counter-control .bi-dash {
            font-size: 18px;
        }

        .btn-rfq {
            background: var(--primary-gradient);
            color: white;
            padding: 12px 35px;
            font-weight: 600;
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px var(--primary-shadow);
            transition: all 0.3s ease;
        }

        .btn-rfq:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 146, 127, 0.3);
        }

        .btn-rfq:active {
            transform: translateY(0);
        }

        .product-table {
            border-spacing: 0 15px !important;
            border-collapse: separate !important;
            margin-top: -15px;
        }

        .product-row {
            background: white;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .product-row td {
            border: none !important;
            padding: 15px !important;
            vertical-align: middle;
        }

        .product-row td:first-child {
            border-radius: 0 15px 15px 0;
        }

        .product-row td:last-child {
            border-radius: 15px 0 0 15px;
        }

        .product-row:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .product-image-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
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

        .product-name {
            color: var(--secondary-color) !important;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 8px;
        }

        .product-detail {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #4b5563;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .product-detail strong {
            color: var(--primary-color) !important;
        }

        .btn-remove {
            width: 35px;
            height: 35px;
            border: none;
            background: #ffebee;
            color: #e53935;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .btn-remove:hover {
            background: #ef5350;
            color: white;
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

        /* تنسيق Checkbox */
        .form-check-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .form-check-input {
            width: 22px;
            height: 22px;
            border: 2px solid var(--primary-color);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .header-checkbox {
            border-color: white !important;
            background-color: transparent;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .header-checkbox:checked {
            background-color: white;
            border-color: white;
        }

        /* تحسين تصميم زر الحذف */
        .btn-outline-danger {
            border: none;
            background: #fff5f5;
            color: var(--danger-color);
            padding: 8px;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-outline-danger:hover {
            background: var(--danger-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
        }

        @keyframes pulse-error {
            0% {
                background-color: transparent;
            }
            50% {
                background-color: rgba(220, 53, 69, 0.1);
            }
            100% {
                background-color: transparent;
            }
        }

        .pulse-error {
            animation: pulse-error 1s ease-in-out;
        }

        /* تحسين حجم وتباعد النص */
        .table thead th {
            letter-spacing: 0.3px;
        }

        /* إضافة تأثير عند التحويم على رؤوس الجدول */
        .table thead th:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            transition: background 0.3s ease;
        }

        /* تحسين تصميم checkbox في الهيدر */
        .table thead .form-check-input {
            width: 22px;
            height: 22px;
            border: 2px solid white;
            background-color: transparent;
        }

        .table thead .form-check-input:checked {
            background-color: white;
            border-color: white;
        }

        /* إضافة تأثير عند التحويم على الصف بالكامل */
        .header-row th {
            position: relative;
            overflow: hidden;
        }

        .header-row th::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.1) 50%, rgba(255,255,255,0) 100%);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }

        .header-row:hover th::after {
            transform: translateX(100%);
        }

        /* تحسين تصميم حالة الفراغ */
        .empty-state {
            padding: 40px;
            text-align: center;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state p {
            margin: 0;
            font-size: 1rem;
        }

        /* تحسين عرض الكمية */
        .order-quantity {
            background: #f8f9fa;
            padding: 6px 15px;
            border-radius: 8px;
            font-weight: 600;
            color: var(--secondary-color);
            width: fit-content;
            margin: 0 auto;
        }

        /* تحسين عرض التفاصيل */
        .product-detail {
            background: #f8f9fa;
            padding: 8px 15px;
            border-radius: 8px;
            line-height: 1.4;
        }

        .product-detail strong {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* تحسين تجربة المستخدم على الأجهزة المحمولة */
        @media (max-width: 768px) {
            /* تغليف الجدول */
            .rfq-container {
                width: 100%;
                overflow-x: scroll;
                -webkit-overflow-scrolling: touch;
                padding: 0;
                margin: 0;
            }

            /* تنسيق الجدول نفسه */
            .table.product-table {
                width: 800px;
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
                width: 50px;
                min-width: 50px;
            }

            .table.product-table th:nth-child(3),
            .table.product-table td:nth-child(3) {
                width: 100px;
                min-width: 100px;
            }

            .table.product-table th:nth-child(4),
            .table.product-table td:nth-child(4) {
                width: 250px;
                min-width: 250px;
            }

            .table.product-table th:nth-child(5),
            .table.product-table td:nth-child(5) {
                width: 200px;
                min-width: 200px;
            }

            .table.product-table th:nth-child(6),
            .table.product-table td:nth-child(6) {
                width: 100px;
                min-width: 100px;
            }

            .table.product-table th:last-child,
            .table.product-table td:last-child {
                width: 50px;
                min-width: 50px;
            }

            /* تحسين شكل الأزرار في خلية الكمية */
            .counter-control {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 5px;
            }

            .counter-control .btn {
                padding: 4px 8px;
            }

            .counter-control input {
                width: 60px;
                text-align: center;
            }
        }
    </style>
@endsection
@section('content')
    <div class="rfq-section">
        <div class="container">
            <!-- Cart Header -->
            <div class="rfq-header">
                <h4 class="mb-3">سلة المشتريات</h4>
                <p class="mb-0 opacity-75">قم بمراجعة المنتجات وتحديث الكميات قبل طلب عروض الأسعار</p>
            </div>

            <!-- Cart Items -->
            <div class="rfq-container">
                <table class="table product-table">
                    <thead>
                        <tr class="header-row">
                            <th scope="col text-center">
                                <input type="checkbox" class="form-check-input header-checkbox" id="check_all">
                            </th>
                            <th scope="col text-center">#</th>
                            <th scope="col">Image</th>
                            <th scope="col">Name</th>
                            <th scope="col">Variant</th>
                            <th scope="col">Quantity</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @if (isset($pending) && count($pending))
                            <form action="{{ route('store.orders.request-for-quotations') }}" method="post" id="form">
                                @csrf
                                @foreach ($pending as $k => $item)
                                    <tr class="product-row">
                                        <th scope="row">
                                            <input type="checkbox" class="form-check-input item-input"
                                                name="detail_key[{{ $item->detail_key_id }}]" data-id="{{ $item->detail_key_id }}">
                                        </th>
                                        <td>{{ $k + 1 }}</td>
                                        <td>
                                            <div class="product-image-wrapper">
                                                <img src="{{ asset('storage/' . $item->detailKey->detail->product->image) }}"
                                                    alt="" class="product-image">
                                            </div>
                                        </td>
                                        @php
                                            $name = config('app.locale') == 'ar' ? 'name_ar' : 'name_en';
                                        @endphp
                                        <td>
                                            <div class="product-name">
                                                {{ $item->detailKey->detail->product->$name }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-detail">
                                                {{ $item->detailKey->detail->name }} :
                                                <strong>{{ $item->detailKey->key }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="counter-control">
                                                <button class="btn ah-btn-control" type="button" id="decreaseBtn"
                                                    onclick="decreaseQty({{ $item->id }})">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="number" class="counter-value form-control ah-input-control"
                                                    name="qty[{{ $item->detail_key_id }}]" min="1"
                                                    value="{{ $item->qty }}" id="productCardQtyModal_{{ $item->id }}">
                                                <button class="btn ah-btn-control" type="button" id="increaseBtn"
                                                    onclick="increaseQty({{ $item->id }})">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-outline-danger btn-sm" data-url="{{ route('store.cart.remove-item', $item->id) }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </form>
                        @else
                            <tr>
                                <th colspan="7" class="text-center py-5">لا يوجد في السلة أي منتجات</th>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <div class="d-flex justify-content-center row my-5 {{ isset($pending) && count($pending) ? '' : 'd-none' }}">
                    <button class="col-md-4 btn btn-rfq" type="button">
                        {{ trans('orders.request for quotations') }}
                    </button>
                </div>
            </div>

            <!-- تحديث جدول الطلبات المرسلة -->
            <div class="col-lg-12 mt-5">
                <div class="rfq-container">
                    <div class="rfq-header">
                        <h4 class="mb-3">طلبات عروض الاسعار المرسلة</h4>
                        <p class="mb-0 opacity-75">عرض جميع طلبات عروض الأسعار التي تم إرسالها مسبقاً</p>
                    </div>

                    <table class="table product-table">
                        <thead>
                            <tr class="header-row">
                                <th scope="col">#</th>
                                <th scope="col">{{ trans('orders.Image') }}</th>
                                <th scope="col">{{ trans('orders.Name') }}</th>
                                <th scope="col">{{ trans('orders.Variant') }}</th>
                                <th scope="col">{{ trans('orders.Quantity') }}</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            @if (isset($open) && count($open))
                                @foreach ($open as $k => $item)
                                    <tr class="product-row">
                                        <td>{{ $k + 1 }}</td>
                                        <td>
                                            <div class="product-image-wrapper">
                                                <img src="{{ asset('storage/' . $item->detailKey->detail->product->image) }}"
                                                    alt="" class="product-image">
                                            </div>
                                        </td>
                                        @php
                                            $name = config('app.locale') == 'ar' ? 'name_ar' : 'name_en';
                                        @endphp
                                        <td>
                                            <div class="product-name">
                                                {{ $item->detailKey->detail->product->$name }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-detail">
                                                {{ $item->detailKey->detail->name }} :
                                                <strong>{{ $item->detailKey->key }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="order-quantity">
                                                {{ $item->qty }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <i class="bi bi-inbox"></i>
                                            <p>لا يوجد طلبات عروض اسعار تم ارسالها</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function increaseQty(id) {
            var qtyInput1 = document.querySelector('#productCardQtyModal_' + id);
            let value = parseInt(qtyInput1.value);
            let newValue = value + 1;
            qtyInput1.value = newValue;
        }

        function decreaseQty(id) {
            var qtyInput1 = document.querySelector('#productCardQtyModal_' + id);
            let value = parseInt(qtyInput1.value);
            if (value <= 1) return
            let newValue = value - 1;
            qtyInput1.value = newValue;
        }

        $('#check_all').on('change', function(e) {
            $("input[type='checkbox']").prop("checked", $(this).is(":checked"));
        })

        $('.btn-outline-danger').click(function(e){
            elem = $(this)
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: $(this).data('url'),
                type: 'delete',
                dataType: 'json',
                success: function(result) {
                    if (result.success) {
                        elem.closest('tr').remove()
                        $('.cart-count').html(parseInt($('.cart-count').html()) - 1)
                    } else {
                        alert('يوجد خطأ ما نرجوا تحديث الصفحة والمحاولة مرة أخرى')
                    }
                },
                error: function(xhr, res) {
                    alert('يوجد خطأ ما نرجوا تحديث الصفحة والمحاولة مرة أخرى')
                    return false;
                }
            });
        })
        $('.btn-rfq').click(function(e){
            if (!$('input.item-input:checked').length) {
                // إضافة تأثير بصري للتنبيه
                $('input.item-input').closest('td').addClass('pulse-error');
                // إظهار رسالة تنبيه
                Swal.fire({
                    title: 'تنبيه!',
                    text: 'فضلا قم بإختيار المنتجات المراد طلب عرض سعر لها',
                    icon: 'warning',
                    confirmButtonText: 'حسناً',
                    confirmButtonColor: '#6c927f'
                });
                
                // إزالة تأثير التنبيه بعد ثانيتين
                setTimeout(() => {
                    $('input.item-input').closest('td').removeClass('pulse-error');
                }, 2000);
                
                return false;
            }
            $('#form').submit();
        })
    </script>
@endpush