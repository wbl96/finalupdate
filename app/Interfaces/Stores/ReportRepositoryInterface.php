<?php

namespace App\Interfaces\Stores;

interface ReportRepositoryInterface
{
    public function purchasesReports(string $period);
    public function paymentsReports(string $period);
}
