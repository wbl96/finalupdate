<?php

namespace App\Providers;

use App\Interfaces\Admins\AdminRepositoryInterface;
use App\Interfaces\Admins\FaqsRepositoryInterface;
use App\Interfaces\Admins\ProductCategoriesRepositoryInterface;
use App\Interfaces\Admins\SiteSettingsRepositoryInterface;
use App\Interfaces\Stores\ProductRepositoryInterface as StoresProductRepositoryInterface;
use App\Interfaces\Stores\SupplierRepositoryInterface as StoresSupplierRepositoryInterface;
use App\Interfaces\Stores\CartRepositoryInterface as StoresCartRepositoryInterface;
use App\Interfaces\Stores\OrderRepositoryInterface as StoresOrderRepositoryInterface;
use App\Interfaces\Stores\ProductCategoryRepositoryInterface as StoresProductCategoryRepositoryInterface;
use App\Interfaces\Stores\ReportRepositoryInterface as StoresReportRepositoryInterface;
use App\Interfaces\Stores\RFQRepositoryInterface;
use App\Interfaces\Suppliers\ClientRepositoryInterface;
use App\Interfaces\Suppliers\OrderRepositoryInterface;
use App\Interfaces\Suppliers\PolicyRepositoryInterface;
use App\Interfaces\Suppliers\ProductCategoryRepositoryInterface;
use App\Interfaces\Suppliers\ProductRepositoryInterface;
use App\Interfaces\Suppliers\ReportRepositoryInterface;
use App\Repositories\Admins\AdminRepository;
use App\Repositories\Admins\FaqsRepository;
use App\Repositories\Admins\ProductCategoriesRepository;
use App\Repositories\Admins\SiteSettingsRepository;
use App\Repositories\Stores\ProductRepository as StoresProductRepository;
use App\Repositories\Stores\RFQRepository;
use App\Repositories\Stores\SupplierRepository as StoresSupplierRepository;
use App\Repositories\Stores\CartRepository as StoresCartRepository;
use App\Repositories\Stores\OrderRepository as StoresOrderRepository;
use App\Repositories\Stores\ProductCategoryRepository as StoresProductCategoryRepository;
use App\Repositories\Stores\ReportRepository as StoresReportRepository;
use App\Repositories\Suppliers\ClientRepository;
use App\Repositories\Suppliers\OrderRepository;
use App\Repositories\Suppliers\PolicyRepository;
use App\Repositories\Suppliers\ProductCategoryRepository;
use App\Repositories\Suppliers\ProductRepository;
use App\Repositories\Suppliers\ReportRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->admins();
        $this->suppliers();
        $this->stores();
    }

    public function admins()
    {
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(FaqsRepositoryInterface::class, FaqsRepository::class);
        $this->app->bind(SiteSettingsRepositoryInterface::class, SiteSettingsRepository::class);
        $this->app->bind(ProductCategoriesRepositoryInterface::class, ProductCategoriesRepository::class);
    }

    public function suppliers()
    {
        // suppliers
        $this->app->bind(ClientRepositoryInterface::class, ClientRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(PolicyRepositoryInterface::class, PolicyRepository::class);
        $this->app->bind(ProductCategoryRepositoryInterface::class, ProductCategoryRepository::class);
        $this->app->bind(ReportRepositoryInterface::class, ReportRepository::class);
    }

    public function stores()
    {
        // stores
        $this->app->bind(StoresProductRepositoryInterface::class, StoresProductRepository::class);
        $this->app->bind(StoresSupplierRepositoryInterface::class, StoresSupplierRepository::class);
        $this->app->bind(StoresCartRepositoryInterface::class, StoresCartRepository::class);
        $this->app->bind(StoresOrderRepositoryInterface::class, StoresOrderRepository::class);
        $this->app->bind(StoresProductCategoryRepositoryInterface::class, StoresProductCategoryRepository::class);
        $this->app->bind(StoresReportRepositoryInterface::class, StoresReportRepository::class);
        $this->app->bind(RFQRepositoryInterface::class, RFQRepository::class);
    }
}
