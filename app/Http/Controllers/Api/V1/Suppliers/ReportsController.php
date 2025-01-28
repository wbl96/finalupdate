<?php

namespace App\Http\Controllers\Api\V1\Suppliers;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Resources\SupplierCollectionsResource;
use App\Http\Resources\SupplierSalesResource;
use App\Http\Resources\SupplierStoresResource;
use App\Interfaces\Suppliers\ReportRepositoryInterface;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
    private ReportRepositoryInterface $reportRepositoryInterface;

    public function __construct(ReportRepositoryInterface $reportRepositoryInterface)
    {
        $this->reportRepositoryInterface = $reportRepositoryInterface;
    }

    public function salesReports(Request $request)
    {
        $period = $request->input('period', 'all');
        $data = $this->reportRepositoryInterface->salesReports($period);
        return ApiResponseClass::sendResponse(SupplierSalesResource::collection($data));
    }
    
    public function paymentsReports(Request $request)
    {
        $period = $request->input('period', 'all');
        $data = $this->reportRepositoryInterface->paymentsReports($period);
        return ApiResponseClass::sendResponse(SupplierCollectionsResource::collection($data));
    }

    public function storesReports(Request $request)
    {
        $period = $request->input('period', 'all');
        $data = $this->reportRepositoryInterface->storesReports($period);
        return ApiResponseClass::sendResponse(SupplierStoresResource::collection($data));
    }
}
