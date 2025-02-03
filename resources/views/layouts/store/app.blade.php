<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    {{-- page title --}}
    <title>@yield('title', trans('global.dashboard')) - {{ trans('global.' . config('app.name', 'subscription system')) }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome-free-6.2.0-web/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    @stack('css')
    {{-- page icon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo_n.svg') }}">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap');

        body {
            font-family: 'Tajawal', sans-serif;
        }

        .navbar {
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-direction: row-reverse;
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .logo img {
            height: 50px;
            transition: transform 0.3s ease;
        }

        .logo img:hover {
            transform: scale(1.05);
        }

        .nav-container {
            display: flex;
            align-items: center;
        }

        .nav-items {
            display: flex;
            list-style-type: none;
            margin: 0;
            padding: 0;
            flex-direction: row-reverse;
        }

        .nav-items li {
            margin-left: 30px;
            position: relative;
        }

        .nav-items a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            font-size: 16px;
            transition: color 0.3s ease;
            position: relative;
            padding-bottom: 5px;
        }

        .nav-items a:hover {
            color: #007bff;
        }

        .nav-items a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: #007bff;
            transition: width 0.3s ease;
        }

        .nav-items a:hover::after {
            width: 100%;
        }

        .nav-icons {
            display: flex;
            align-items: center;
            margin-right: 30px;
        }

        .cart-icon,
        .user-icon-container {
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            margin-left: 20px;
        }

        .cart-icon:hover,
        .user-icon-container:hover {
            transform: scale(1.1);
        }

        .cart-icon {
            font-size: 1.4rem;
            color: #333;
        }

        .cart-count, .rfq-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .cart-icon:hover .cart-count, .cart-icon:hover .rfq-count  {
            transform: scale(1.2);
        }

        .user-icon-container {
            width: 40px;
            height: 40px;
            position: relative;
            overflow: hidden;
        }

        .user-icon {
            width: 100%;
            height: 100%;
            background: #6C927F;
            /* Changed to the requested color */
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .user-icon::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="35" r="20" fill="white"/><path d="M15,85 Q50,65 85,85" fill="white"/></svg>') no-repeat center center;
            background-size: 70%;
            opacity: 0.7;
            transition: all 0.3s ease;
        }

        .user-icon:hover {
            background: #5A7B69;
            /* Darker shade for hover effect */
        }

        .tooltip {
            visibility: hidden;
            width: 120px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px 0;
            position: absolute;
            z-index: 1;
            bottom: -35px;
            left: 50%;
            margin-left: -60px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .cart-icon:hover .tooltip,
        .user-icon-container:hover .tooltip {
            visibility: visible;
            opacity: 1;
        }

        .product-card {
            height: 100%;
        }

        .product-card .card-body {
            padding: 1rem 1rem 0 1rem;
            display: flex;
            flex-direction: column;
        }

        .product-details {
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .product-name {
            margin-bottom: 8px;
        }

        .supplier-name {
            height: 42px;
            overflow: hidden;
        }

        .price-info {
            margin-top: auto;
            padding: 4px 8px;
            margin: auto 0 0 0;
            background-color: #f8f9fa;
            border-bottom-left-radius: inherit;
            border-bottom-right-radius: inherit;
        }

        .expected-price {
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .price-label, .currency {
            font-size: 0.75rem;
        }

        .price-value {
            font-size: 0.8rem;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .product-image-wrapper {
            position: relative;
            overflow: hidden;
        }

        .product-image-wrapper img {
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image-wrapper img {
            transform: scale(1.05);
        }

        .product-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .product-card:hover .product-overlay {
            opacity: 1;
        }

        .btn-quick-view {
            background-color: white;
            color: #333;
            border: none;
            padding: 8px 16px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-quick-view:hover {
            background-color: #6C927F;
            color: white;
        }

        .product-card .card-title {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .product-card .card-text {
            font-size: 0.9rem;
            color: #666;
        }

        .product-card .btn-primary {
            background-color: #6C927F;
            border-color: #6C927F;
            transition: all 0.3s ease;
        }

        .product-card .btn-primary:hover {
            background-color: #5A7B69;
            border-color: #5A7B69;
        }
        .wallet-icon-container {
            position: relative;
            cursor: pointer;
        }

        .wallet-icon-wrapper {
            transition: all 0.3s ease;
        }

        .wallet-icon-container:hover .wallet-icon-wrapper {
            box-shadow: 0 0 15px rgba(108, 146, 127, 0.5);
            transform: scale(1.05);
        }

        .wallet-balance {
            transition: all 0.3s ease;
        }

        .wallet-icon-container:hover .wallet-balance {
            transform: scale(1.1);
        }


        .mt-5 {
            margin-top: 3rem !important;
        }

        .store-icon-container {
            position: relative;
            margin-right: 20px;
        }

        .store-icon-wrapper {
            background-color: #6C927F;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .store-icon-wrapper i {
            color: white;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .store-status {
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #28a745;
            color: white;
            font-size: 0.6rem;
            padding: 2px 6px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .store-icon-container:hover .store-icon-wrapper {
            box-shadow: 0 0 15px rgba(108, 146, 127, 0.5);
        }

        .store-icon-container:hover .store-status {
            bottom: 2px;
        }

        .store-tooltip {
            position: absolute;
            top: 120%;
            right: 50%;
            transform: translateX(50%);
            background-color: #333;
            color: white;
            padding: 10px;
            border-radius: 5px;
            width: 200px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .store-tooltip h6 {
            margin-bottom: 5px;
        }

        .store-tooltip p {
            font-size: 0.8rem;
            margin-bottom: 10px;
        }

        .store-tooltip .fa-star {
            color: #ffc107;
        }

        .store-icon-container:hover .store-tooltip {
            opacity: 1;
            visibility: visible;
            top: 100%;
        }

        .store-tooltip::before {
            content: '';
            position: absolute;
            bottom: 100%;
            right: 50%;
            transform: translateX(50%);
            border: 8px solid transparent;
            border-bottom-color: #333;
        }

        .store-tooltip .btn-outline-light:hover {
            background-color: #6C927F;
            border-color: #6C927F;
        }

        .cart-icon-container {
            position: relative;
            margin-right: 20px;
        }

        .cart-icon-wrapper {
            background-color: #6C927F;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            transition: all 0.3s ease;
        }

        .cart-icon-wrapper i {
            color: white;
            font-size: 1.2rem;
        }

        .cart-count, .rfq-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #e74c3c;
            color: white;
            font-size: 0.7rem;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .cart-icon-container:hover .cart-icon-wrapper {
            box-shadow: 0 0 15px rgba(108, 146, 127, 0.5);
            transform: scale(1.05);
        }

        /* Adjust the existing store icon styles for consistency */
        .store-icon-wrapper {
            background-color: #6C927F;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .store-icon-wrapper i {
            color: white;
            font-size: 1.2rem;
        }

        .store-icon-container:hover .store-icon-wrapper {
            box-shadow: 0 0 15px rgba(108, 146, 127, 0.5);
            transform: scale(1.05);
        }

        /* Adjust the nav-icons container for better alignment */
        .nav-icons {
            display: flex;
            align-items: center;
        }

        .navbar-scrolled {
            padding: 5px 30px;
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-toggler:focus {
            text-decoration: none;
            outline: 0;
            box-shadow: unset;
        }
        .table.table-bordered thead *:not(input) {
            background-color: #6c927f !important;
            color: #fff;
        }
                /* Enhanced Section Tabs Styling */
        .section-tabs {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 15px;
            margin: 0;
            overflow-x: auto;
            background: #fff;
            border-bottom: 2px solid #f1f5f9;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .section-tabs::-webkit-scrollbar {
            height: 0;
            width: 0;
        }

        .tab-link {
            padding: 12px 24px;
            color: #64748b;
            text-decoration: none;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: #f1f5f9;
            position: relative;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
            border: 2px solid transparent;
        }

        .tab-link:hover {
            color: #1e293b;
            background: #e2e8f0;
            transform: translateY(-1px);
        }

        .tab-link.active {
            background: #6C927F;
            color: white;
            box-shadow: 0 4px 15px rgba(108, 146, 127, 0.3);
            border: 2px solid #5a7b69;
        }

        .tab-link i {
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .tab-link:hover i {
            transform: scale(1.1);
        }

        /* تأثيرات متقدمة */
        .tab-link::before {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 14px;
            background: linear-gradient(45deg, #6C927F, #5a7b69);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .tab-link:hover::before {
            opacity: 0.1;
        }

        .tab-link.active::before {
            opacity: 1;
        }

        /* تحسينات الموبايل */
        @media (max-width: 768px) {
            .section-tabs {
                padding: 12px;
                gap: 6px;
            }

            .tab-link {
                padding: 10px 18px;
                font-size: 0.9rem;
                border-radius: 10px;
            }

            .tab-link i {
                font-size: 1.1rem;
            }
        }

        /* تحسين القائمة المنسدلة */
        .tab.dropdown .tab-link {
            padding-right: 40px;
        }

        .tab.dropdown .tab-link::after {
            display: none;
        }

        .tab-link.dropdown-toggle::after {
            margin-left: 8px;
            vertical-align: middle;
            border-top: 0.3em solid;
            border-right: 0.3em solid transparent;
            border-left: 0.3em solid transparent;
            transition: transform 0.3s ease;
        }

        .tab-link.dropdown-toggle:hover::after {
            transform: rotate(180deg);
        }

        .tab-link.active.dropdown-toggle::after {
            border-top-color: white;
        }

        /* تأثيرات إضافية */
        .tab-link:active {
            transform: scale(0.97);
        }

        .tab-link.active:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 146, 127, 0.4);
        }

        /* تحسين التباين والقراءة */
        .tab-link {
            text-shadow: 0 1px 1px rgba(0,0,0,0.1);
        }

        .tab-link.active {
            text-shadow: 0 1px 1px rgba(0,0,0,0.2);
        }

        /* إضافة تأثير نبض للتاب النشط */
        @keyframes tabPulse {
            0% { box-shadow: 0 4px 15px rgba(108, 146, 127, 0.3); }
            50% { box-shadow: 0 4px 20px rgba(108, 146, 127, 0.5); }
            100% { box-shadow: 0 4px 15px rgba(108, 146, 127, 0.3); }
        }

        .tab-link.active {
            animation: tabPulse 2s infinite;
        }

        /* تحسينات إضافية */
        .btn.icon-btn {
            width: 40px;
            height: 40px;
            padding: 0;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 50%;
            background: #6C927F;
            color: white;
            border: none;
            transition: all 0.3s ease;
            position: relative;
            z-index: 50;
        }

        .btn.icon-btn i {
            font-size: 1rem;
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        /* تنسيقات خاصة للموبايل */
        @media (max-width: 768px) {
            .btn.icon-btn {
                width: 35px;
                height: 35px;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }

            .btn.icon-btn i {
                font-size: 0.9rem;
                display: inline-block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }

            /* تأكيد ظهور الأيقونة في الموبايل */
            .fa-shopping-cart,
            .fa-cart-shopping {
                display: inline-block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
        }

        /* تحسينات إضافية */
        .btn.icon-btn:hover {
            background: #5a7b69;
            transform: scale(1.05);
        }

        .btn.icon-btn:active {
            transform: scale(0.95);
        }

        /* تأكيد عام لظهور أيقونات السلة */
        .fa-shopping-cart,
        .fa-cart-shopping {
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        /* حذف تنسيقات زر الإغلاق */
        .mobile-close-btn {
            display: none; /* إخفاء زر الإغلاق */
        }

        .mobile-header {
            display: none; /* إخفاء الهيدر المتحرك الذي يحتوي على زر الإغلاق */
        }

        /* تعديل تنسيق القائمة المنسدلة للموبايل */
        @media (max-width: 768px) {
            .dropdown-content {
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                width: 100%;
                max-height: 85vh;
                background: #fff;
                border-radius: 25px 25px 0 0;
                box-shadow: 0 -4px 30px rgba(0,0,0,0.15);
                animation: slideUp 0.3s ease;
                z-index: 1000;
                padding-top: 15px; /* إضافة padding بدلاً من الهيدر */
            }

            .subcategories-list {
                padding: 0; /* إزالة padding إضافي */
            }
        }

        /* تصحيح تنسيقات زر الإغلاق للموبايل */
        @media (max-width: 768px) {
            .mobile-header {
                display: flex;
                padding: 15px 20px;
                border-bottom: 1px solid #eee;
                justify-content: space-between;
                align-items: center;
                background: #fff;
                border-radius: 25px 25px 0 0;
            }

            .mobile-close-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 35px;
                height: 35px;
                border: none;
                background: #f5f5f5;
                border-radius: 50%;
                color: #666;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .mobile-close-btn:hover {
                background: #eee;
            }

            .dropdown-content {
                padding-top: 0; /* إزالة padding لوجود الهيدر */
            }
        }

        /* تصحيح مشكلة السهمين في التاب */
        .tab.dropdown .tab-link::before {
            content: '\f107';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            transition: all 0.3s ease;
        }

        /* إزالة السهم الافتراضي من عناصر select */
        .tab.dropdown .tab-link.dropdown-toggle::after {
            display: none;
        }

        /* تحسين شكل القائمة المنسدلة */
        .dropdown-content {
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .dropdown-content {
                border-radius: 25px 25px 0 0;
            }
        }

        /* تحسين التناسق مع الحاوية */
        .container-fluid .section-tabs {
            max-width: 100%;
            box-sizing: border-box;
        }

        /* تحسين تصميم القائمة المنسدلة */
        .dropdown-content {
            position: absolute;
            top: 100%;
            left: 0;
            background: #fff;
            min-width: 220px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid #f1f5f9;
            overflow: hidden;
            z-index: 1000;
            transform-origin: top;
            animation: dropdownFadeIn 0.3s ease;
        }

        @keyframes dropdownFadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* تصميم رأس القائمة المنسدلة للموبايل */
        .mobile-header {
            display: none;
            padding: 15px 20px;
            border-bottom: 1px solid #f1f5f9;
            background: #fff;
        }

        .mobile-header h6 {
            color: #1e293b;
            font-weight: 600;
            margin: 0;
        }

        .mobile-close-btn {
            width: 32px;
            height: 32px;
            border: none;
            background: #f8fafc;
            border-radius: 10px;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .mobile-close-btn:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        /* تصميم عناصر القائمة المنسدلة */
        .dropdown-content .subcategories-list {
            list-style: none;
            padding: 8px 0;
            margin: 0;
        }

        .dropdown-content .subcategories-list li {
            padding: 0;
            margin: 0;
        }

        .dropdown-content .subcategories-list a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #64748b;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .dropdown-content .subcategories-list a:hover {
            background: #f8fafc;
            color: #6C927F;
            padding-left: 25px;
        }

        .dropdown-content .subcategories-list i {
            margin-right: 10px;
            font-size: 1.1rem;
            color: #94a3b8;
            transition: all 0.3s ease;
        }

        .dropdown-content .subcategories-list a:hover i {
            color: #6C927F;
            transform: translateX(3px);
        }

        /* تحسينات الموبايل */
        @media (max-width: 768px) {
            .dropdown-content {
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                width: 100%;
                max-height: 85vh;
                border-radius: 25px 25px 0 0;
                transform: translateY(0);
            }

            .mobile-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .dropdown-content .subcategories-list {
                max-height: calc(85vh - 60px);
                overflow-y: auto;
            }

            .dropdown-content .subcategories-list::-webkit-scrollbar {
                width: 5px;
            }

            .dropdown-content .subcategories-list::-webkit-scrollbar-thumb {
                background: #e2e8f0;
                border-radius: 10px;
            }
        }

        /* تأثير الخلفية عند فتح القائمة في الموبايل */
        .dropdown-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(4px);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .dropdown-open .dropdown-backdrop {
            opacity: 1;
        }

        /* تنسيق قائمة الاتصال */
        .contact-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            min-width: 250px;
            display: none;
            z-index: 1000;
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .contact-menu-header {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            background: #f8fafc;
        }

        .contact-menu-header h6 {
            color: #1e293b;
            font-weight: 600;
        }

        .contact-menu-items {
            padding: 8px 0;
        }

        .contact-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #64748b;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .contact-item:hover {
            background: #f8fafc;
            color: #6C927F;
        }

        .contact-item i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        .item-title {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.9rem;
        }

        .item-subtitle {
            color: #64748b;
            font-size: 0.85rem;
        }

        .email-item i {
            color: #e11d48;
        }

        .whatsapp-item i {
            color: #25d366;
        }

        /* تحسينات الموبايل */
        @media (max-width: 768px) {
            .contact-menu {
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                top: auto;
                width: 100%;
                border-radius: 25px 25px 0 0;
                transform: translateY(100%);
                transition: transform 0.3s ease;
            }

            .contact-menu.show {
                transform: translateY(0);
            }
        }
    </style>
        
    </style>
    @yield('css')
</head>

<body>
    @include('layouts.store.navbar')
    <div class="bg-light" id="wrapper">
        {{-- page content wrapper --}}
        <div id="page-content-wrapper" style="min-height:100vh">
            {{-- page container --}}
            <div class="my-3 container px-4">
                {{-- error messages --}}
                @include('layouts.global.messages')
                {{-- page content --}}
                @yield('content')
            </div>
        </div>
    </div>



    {{-- include loading indicator --}}
    @include('layouts.global.loading_indicatore')
    {{-- include modal delete if user authenticated --}}
    @includeWhen(Auth::check(), 'layouts.global.modal_delete')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        window.addEventListener('scroll', function() {
            var navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });

        var el = document.getElementById("wrapper");
        var toggleButton = document.getElementById("menu-toggle");
        if (toggleButton)
            toggleButton.onclick = function() {
                el.classList.toggle("toggled");
            };

        function showNavbar() {
            if ($('#navbarNav').hasClass('show')) {
                $('#navbarNav').removeClass('show')
            } else {
                $('#navbarNav').addClass('show')
            }
        }
    </script>

    <script src="{{ asset('assets/js/app_2.js') }}"></script>
    @stack('script')
    <script type="module">
        // Import the functions you need from the SDKs you need
        import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-app.js";
        import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-messaging.js";
        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries

        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
        const firebaseConfig = {
            apiKey: "AIzaSyB18bAU1e77zdI_X7BkCt7Jtd1M2oJyx40",
            authDomain: "wbl-web.firebaseapp.com",
            projectId: "wbl-web",
            storageBucket: "wbl-web.firebasestorage.app",
            messagingSenderId: "188305287604",
            appId: "1:188305287604:web:2e847ed16e7e3c0803aae1",
            measurementId: "G-PPR6NRQL39"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const messaging = getMessaging();

        navigator.serviceWorker.register("{{ asset('assets/js/serviceWorker.js') }}").then(registration => {
            getToken(messaging, {
                serviceWorkerRegistration: registration,
                vapidKey: 'BDQtIF6bQywLF1fYa0afK35KbmbgxQ-Pa8-x_UYxyXXLEgJfH5XjP35vgKZ5HFKVVJFU_gc8e6mmLxD5Mwf1KWA'
            }).then((currentToken) => {
                if (currentToken) {
                    // Send the token to your server and update the UI if necessary
                    updateFCM(currentToken)
                } else {
                    // Show permission request UI
                    console.log('No registration token available. Request permission to generate one.');
                    // ...
                }
            }).catch((err) => {
                console.log('An error occurred while retrieving token. ', err);
                // ...
            });
        })
    </script>
    <script>
        function updateFCM(fcm_token){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('store.updateFCM') }}",
                type: 'post',
                data: {
                    'fcm_token': fcm_token,
                },
                dataType: 'json'
            });
        }
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdowns = document.querySelectorAll('.dropdown');
        
        dropdowns.forEach(dropdown => {
            const link = dropdown.querySelector('.tab-link');
            const content = dropdown.querySelector('.dropdown-content');
            
            if (link && content) {
                // للكمبيوتر - عند التحويم
                if (window.innerWidth >= 769) {
                    dropdown.addEventListener('mouseenter', function() {
                        content.style.display = 'block';
                        setTimeout(() => {
                            content.classList.add('show');
                        }, 10);
                    });

                    dropdown.addEventListener('mouseleave', function() {
                        content.classList.remove('show');
                        setTimeout(() => {
                            content.style.display = 'none';
                        }, 200);
                    });
                }
                
                // للموبايل - عند النقر
                link.addEventListener('click', function(e) {
                    if (window.innerWidth < 769) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        document.querySelectorAll('.dropdown-content').forEach(menu => {
                            if (menu !== content) {
                                menu.style.display = 'none';
                            }
                        });
                        
                        content.style.display = 'block';
                        document.body.classList.add('dropdown-open');
                    }
                });
            }
        });
        
        // تحديث دالة إغلاق القائمة المنسدلة للموبايل
        window.closeDropdown = function(button) {
            if (window.innerWidth < 769) {
                const dropdownContent = button.closest('.dropdown-content');
                if (dropdownContent) {
                    dropdownContent.style.display = 'none';
                    document.body.classList.remove('dropdown-open');
                }
            }
        };

        // إغلاق القائمة عند النقر خارجها في الموبايل
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 769) {
                if (!e.target.closest('.dropdown') && !e.target.closest('.dropdown-content')) {
                    document.querySelectorAll('.dropdown-content').forEach(menu => {
                        menu.style.display = 'none';
                    });
                    document.body.classList.remove('dropdown-open');
                }
            }
        });
    });
    </script>
    <script>
    function toggleContactMenu(event) {
        event.preventDefault();
        const menu = document.getElementById('contactMenu');
        const isShown = menu.style.display === 'block';
        
        // إغلاق جميع القوائم المنسدلة الأخرى
        document.querySelectorAll('.dropdown-content').forEach(el => {
            el.style.display = 'none';
        });

        // تبديل حالة القائمة
        menu.style.display = isShown ? 'none' : 'block';
        
        // إضافة خلفية معتمة للموبايل
        if (window.innerWidth <= 768) {
            menu.classList.toggle('show');
            document.body.classList.toggle('dropdown-open');
        }
    }

    // إغلاق القائمة عند النقر خارجها
    document.addEventListener('click', function(e) {
        const menu = document.getElementById('contactMenu');
        const button = e.target.closest('.nav-item.dropdown');
        
        if (!button && menu.style.display === 'block') {
            menu.style.display = 'none';
            menu.classList.remove('show');
            document.body.classList.remove('dropdown-open');
        }
    });
    </script>
</body>

</html>
