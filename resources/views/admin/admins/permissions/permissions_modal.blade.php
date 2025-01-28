@php
    use App\Enums\admin\PermissionsEnum;
    $permissionsGroups = [
        'dashboard' => [
            'title' => 'global.dashboard',
            'permissions' => PermissionsEnum::dashboardPermissions(),
        ],
        'admins' => [
            'title' => 'admins.the admins',
            'permissions' => PermissionsEnum::adminsPermissions(),
        ],
        'permissions' => [
            'title' => 'admins.permissions',
            'permissions' => PermissionsEnum::permissionsPermissions(),
        ],
        'users' => [
            'title' => 'users.the users',
            'permissions' => PermissionsEnum::usersPermissions(),
        ],
        'services' => [
            'title' => 'services.the services',
            'permissions' => PermissionsEnum::servicesPermissions(),
        ],
        'orders' => [
            'title' => 'orders.the orders',
            'permissions' => PermissionsEnum::ordersPermissions(),
        ],
        'sugg_comp' => [
            'title' => 'comp_sugg.the comps & suggs',
            'permissions' => PermissionsEnum::suggCompPermissions(),
        ],
        'settings' => [
            'title' => 'settings.settings',
            'permissions' => PermissionsEnum::settingsPermissions(),
        ],
        'terms_conditions' => [
            'title' => 'terms_conditions.terms & conditions',
            'permissions' => PermissionsEnum::termsConditionsPermissions(),
        ],
        'privacy_policy' => [
            'title' => 'privacy_policy.privacy policy',
            'permissions' => PermissionsEnum::privacyPolicyPermissions(),
        ],
        'who_us' => [
            'title' => 'who_us.who us',
            'permissions' => PermissionsEnum::whoUsPermissions(),
        ],
        'connection' => [
            'title' => 'connection.connection info',
            'permissions' => PermissionsEnum::connectionPermissions(),
        ],
    ];
@endphp

<div class="modal fade" id="permissionModal" tabindex="-1" aria-labelledby="permissionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered p-5 modal-fullscreen">
        <form action="{{ route('admin.admins.updatePermissions', ['id' => ':TARGET']) }}" method="post"
            class="modal-content" id="modal_permission">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">صلاحيات الاداراي (<span id="admin_name"></span>)</h5>
                <button type="button" @class(['btn-close', 'me-auto ms-0' => app()->getLocale() == 'ar']) data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <input type="hidden" name="admin_id" id="admin_id">
            <div class="d-none" id="permisstions_data" data-permissions="{{ $permissions }}"></div>
            <div class="modal-body">
                <table class="table table-striped table-hover table-bordered">
                    <tr class="table-primary">
                        <th>
                            <input type="checkbox" id="all-check" onchange="checkAll(this, 'all-check')"
                                value="disable">
                            <label for="all-check">عنوان القائمة</label>
                        </th>
                        <th class="text-center">
                            <input type="checkbox" id="show-check" onchange="checkAll(this, 'show-check')"
                                value="disable">
                            <label for="show-check">{{ trans('global.show') }}</label>
                        </th>
                        <th class="text-center">
                            <input type="checkbox" id="add-check" onchange="checkAll(this, 'add-check')"
                                value="disable">
                            <label for="add-check">{{ trans('global.add') }}</label>
                        </th>
                        <th class="text-center">
                            <input type="checkbox" id="update-check" onchange="checkAll(this, 'update-check')"
                                value="disable">
                            <label for="update-check">{{ trans('global.edit') }}</label>
                        </th>
                        <th class="text-center">
                            <input type="checkbox" id="delete-check" onchange="checkAll(this, 'delete-check')"
                                value="disable">
                            <label for="delete-check">{{ trans('global.delete') }}</label>
                        </th>
                        <th class="text-center">
                            <input type="checkbox" id="excel-check" onchange="checkAll(this, 'excel-check')"
                                value="disable">
                            <label for="excel-check">{{ trans('global.excel') }}</label>
                        </th>
                        <th class="text-center">
                            <input type="checkbox" id="print-check" onchange="checkAll(this, 'print-check')"
                                value="disable">
                            <label for="print-check">{{ trans('global.print') }}</label>
                        </th>
                    </tr>
                    @foreach ($permissionsGroups as $groupName => $groupPermissions)
                        <tr>
                            <td>
                                <input type="checkbox" class="{{ $groupName }}-check" id="{{ $groupName }}-id"
                                    onchange="checkAll(this, '{{ $groupName }}-check')" value="disable">
                                <label for="{{ $groupName }}-id">{{ trans($groupPermissions['title']) }}</label>
                            </td>

                            @php
                                $permissionsMap = [
                                    'show' => $groupName . '_show',
                                    'add' => $groupName . '_add',
                                    'update' => $groupName . '_update',
                                    'delete' => $groupName . '_delete',
                                    'excel' => $groupName . '_excel',
                                    'print' => $groupName . '_print',
                                ];
                            @endphp

                            {{-- Display permissions under their respective columns --}}
                            @foreach ($permissionsMap as $key => $permission)
                                <td class="text-center">
                                    @if (in_array($permission, $groupPermissions['permissions']))
                                        @php
                                            $key = explode('_', $permission);
                                            $key = end($key);
                                        @endphp
                                        <input type="checkbox"
                                            class="{{ $permission }}-check {{ $groupName }}-check {{ $key }}-check"
                                            name="permissions[]" value="{{ $permission }}"
                                            @checked($admin->hasDirectPermission($permission)) data-s="{{ $permission }}">
                                    @endif

                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">{{ trans('global.save') }}</button>
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{ trans('global.close') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
    function checkAll(checkbox, className) {
        var isChecked = $(checkbox).is(':checked')
        if (className == 'all-check') {
            $(':checkbox').prop('checked', isChecked);
            return;
        }

        const checkboxes = document.querySelectorAll(`.${className}`);
        checkboxes.forEach(function(item) {
            item.checked = checkbox.checked;
        });
    }
</script>
