@extends('layouts.global.app')

@section('sidebar')
    @include('layouts.supplier.sidebare')
@endsection
@section('topbar')
    @include('layouts.supplier.topbar')
@endsection

@section('content')
    {{-- invoices department --}}
    {{-- <div class="container mt-4 receipt">
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="3">الطلبات - طلب رقم 11111</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>اسم المتجر: سوبر ماركت الورود</td>
                            <td>تاريخ الطلب: 20-12-2022</td>
                        </tr>
                        <tr>
                            <td>حالة الطلب: طلب جديد
                                <button class="btn btn-import btn-action btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editStatusModal">تعديل</button>
                            </td>
                            <td>حالة الدفع: مستحق</td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>الصنف</th>
                            <th>الكمية</th>
                            <th>السعر</th>
                            <th>الاجمالي</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>بيبسى</td>
                            <td>10 كرتون</td>
                            <td>40</td>
                            <td>400</td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered">
                    <p class="text-primary d-inline-flex">ارشيف دفعات الفاتوره </p>
                    <button class="btn btn-import btn-add btn-sm mx-2" data-bs-toggle="modal"
                        data-bs-target="#addPaymentModal">إضافة دفعة</button>
                    <tbody>
                        <tr>
                            <td>المبلغ الإجمالي:</td>
                            <td>400</td>
                        </tr>
                        <tr>
                            <td>المدفوع:</td>
                            <td>300</td>
                        </tr>
                        <tr>
                            <td>المتبقي:</td>
                            <td>100</td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>المبلغ المدفوع</th>
                            <th>المبلغ المتبقي</th>
                            <th>تاريخ الدفع</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>300</td>
                            <td>100</td>
                            <td>18-05-2024</td>
                        </tr>
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    <button class="btn btn-import btn-print btn-sm">طباعة</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editStatusModal" tabindex="-1" aria-labelledby="editStatusLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="editStatusLabel">تعديل حالة الطلب</h5>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="status" class="form-label">حالة الطلب</label>
                            <select class="form-select" id="status">
                                <option value="مسدد">مسدد</option>
                                <option value="مستحق">مستحق</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="reason" class="form-label">سبب الرفض</label>
                            <input type="text" class="form-control" id="reason">
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-import btn-action">حفظ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="addPaymentLabel">إضافة دفعة</h5>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="remainingAmount" class="form-label">المبلغ المتبقي</label>
                            <input type="text" class="form-control" id="remainingAmount">
                        </div>
                        <div class="mb-3">
                            <label for="collectedAmount" class="form-label">المبلغ المحصل</label>
                            <input type="text" class="form-control" id="collectedAmount">
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-import btn-action">حفظ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- advanced search --}}
    {{-- <button class="btn btn-import btn-advanced-search btn-sm" data-bs-toggle="modal" data-bs-target="#advancedSearchModal">
        بحث متقدم
    </button>
    <div class="modal fade" id="advancedSearchModal" tabindex="-1" aria-labelledby="advancedSearchLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="advancedSearchLabel">بحث متقدم</h5>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="productName" class="form-label">اسم المنتج</label>
                                <input type="text" class="form-control" id="productName">
                            </div>
                            <div class="col-md-6">
                                <label for="storeName" class="form-label">اسم المتجر</label>
                                <input type="text" class="form-control" id="storeName">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fromDate" class="form-label">من تاريخ</label>
                                <input type="date" class="form-control" id="fromDate">
                            </div>
                            <div class="col-md-6">
                                <label for="toDate" class="form-label">الى تاريخ</label>
                                <input type="date" class="form-control" id="toDate">
                            </div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-import btn-search">بحث</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
