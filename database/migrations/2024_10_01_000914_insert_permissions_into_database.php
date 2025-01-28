<?php

use App\Enums\Admin\PermissionsEnum;
use App\Models\Admin;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // get all permissions from permissions enum
        $permissions = PermissionsEnum::cases();

        // loop on it
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission->value],
                ['name' => $permission->value, 'guard_name' => 'admin'],
            );
        }

        // get all admins that has role Super admin to give them all permissions
        $superAdmins = Admin::whereRelation('department', 'id', 1)->get();

        // loop on all
        foreach ($superAdmins as $admin) {
            $admin->givePermissionTo(Permission::all());
        }
    }
};
