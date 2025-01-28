<?php

namespace App\Interfaces\Suppliers;

interface ReportRepositoryInterface
{
    public function salesReports(string $period);
    public function paymentsReports(string $period);
    public function storesReports(string $period);
}
