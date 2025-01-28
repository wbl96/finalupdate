<?php

namespace App\Http\Controllers\Api\V1\Stores;

use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Resources\StorePaymentsResource;
use App\Http\Resources\StorePurchasesResource;
use App\Interfaces\Stores\ReportRepositoryInterface;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    private ReportRepositoryInterface $reportRepositoryInterface;

    public function __construct(ReportRepositoryInterface $reportRepositoryInterface)
    {
        $this->reportRepositoryInterface = $reportRepositoryInterface;
    }

    public function purchasesReports(Request $request)
    {
        $period = $request->input('period', 'all');
        $data = $this->reportRepositoryInterface->purchasesReports($period);
        return ApiResponseClass::sendResponse(StorePurchasesResource::collection($data));
    }

    public function paymentsReports(Request $request)
    {
        $period = $request->input('period', 'all');
        $data = $this->reportRepositoryInterface->paymentsReports($period);
        return ApiResponseClass::sendResponse(StorePaymentsResource::collection($data));
    }
}
