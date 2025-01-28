<?php

namespace App\Enums\Admin;

enum PermissionsEnum: string
{
        // dashboard`s permissions
    case DASHBOARD_SHOW = 'dashboard_show';
        // admins` permissions
    case ADMINS_SHOW = 'admins_show';
    case ADMINS_ADD = 'admins_add';
    case ADMINS_UPDATE = 'admins_update';
    case ADMINS_DELETE = 'admins_delete';
        // permissions` permissions
    case PERMISSIONS_ADD = 'permissions_add';
    case PERMISSIONS_UPDATE = 'permissions_update';
        // users` permissions
    case USERS_SHOW = 'users_show';
    case USERS_ADD = 'users_add';
    case USERS_UPDATE = 'users_update';
    case USERS_DELETE = 'users_delete';
        // services` permissions
    case SERVICES_SHOW = 'services_show';
    case SERVICES_ADD = 'services_add';
    case SERVICES_UPDATE = 'services_update';
    case SERVICES_DELETE = 'services_delete';
        // orders` permissions
    case ORDERS_SHOW = 'orders_show';
    case ORDERS_ADD = 'orders_add';
    case ORDERS_UPDATE = 'orders_update';
    case ORDERS_DELETE = 'orders_delete';
    case ORDERS_EXCEL = 'orders_excel';
    case ORDERS_PRINT = 'orders_print';
        // suggestions and complaints` permissions
    case SUGG_COMP_SHOW = 'sugg_comp_show';
    case SUGG_COMP_ADD = 'sugg_comp_add';
    case SUGG_COMP_UPDATE = 'sugg_comp_update';
    case SUGG_COMP_DELETE = 'sugg_comp_delete';
    case SUGG_COMP_EXCEL = 'sugg_comp_excel';
    case SUGG_COMP_PRINT = 'sugg_comp_print';
        // settings` permissions
    case SETTINGS_SHOW = 'settings_show';
    case SETTINGS_ADD = 'settings_add';
    case SETTINGS_UPDATE = 'settings_update';
    case SETTINGS_DELETE = 'settings_delete';
        // terms and conditions` permissions
    case TERMS_CONDITIONS_SHOW = 'terms_conditions_show';
    case TERMS_CONDITIONS_ADD = 'terms_conditions_add';
    case TERMS_CONDITIONS_UPDATE = 'terms_conditions_update';
        // terms of use and privacy policy permissiodeletens
    case PRIVACY_POLICY_SHOW = 'privacy_policy_show';
    case PRIVACY_POLICY_ADD = 'privacy_policy_add';
    case PRIVACY_POLICY_UPDATE = 'privacy_policy_update';
    case PRIVACY_POLICY_DELETE = 'privacy_policy_delete';
        // who us permissions
    case WHO_US_SHOW = 'who_us_show';
    case WHO_US_UPDATE = 'who_us_update';
        // connection data permissions
    case CONNECTION_SHOW = 'connection_show';
    case CONNECTION_ADD = 'connection_add';
    case CONNECTION_UPDATE = 'connection_update';
    case CONNECTION_DELETE = 'connection_delete';
    case CONNECTION_EXCEL = 'connection_excel';
    case CONNECTION_PRINT = 'connection_print';

    public function label(): string
    {
        return match ($this) {
            static::DASHBOARD_SHOW => 'dashboard_show',
            static::ADMINS_SHOW => 'admins_show',
            static::ADMINS_ADD => 'admins_add',
            static::ADMINS_UPDATE => 'admins_update',
            static::ADMINS_DELETE => 'admins_delete',
            static::PERMISSIONS_ADD => 'permissions_add',
            static::PERMISSIONS_UPDATE => 'permissions_update',
            static::USERS_SHOW => 'users_show',
            static::USERS_ADD => 'users_add',
            static::USERS_UPDATE => 'users_update',
            static::USERS_DELETE => 'users_delete',
            static::SERVICES_SHOW => 'services_show',
            static::SERVICES_ADD => 'services_add',
            static::SERVICES_UPDATE => 'services_update',
            static::SERVICES_DELETE => 'services_delete',
            static::ORDERS_SHOW => 'orders_show',
            static::ORDERS_ADD => 'orders_add',
            static::ORDERS_UPDATE => 'orders_update',
            static::ORDERS_DELETE => 'orders_delete',
            static::ORDERS_EXCEL => 'orders_excel',
            static::ORDERS_PRINT => 'orders_print',
            static::SUGG_COMP_SHOW => 'sugg_comp_show',
            static::SUGG_COMP_ADD => 'sugg_comp_add',
            static::SUGG_COMP_UPDATE => 'sugg_comp_update',
            static::SUGG_COMP_DELETE => 'sugg_comp_delete',
            static::SUGG_COMP_EXCEL => 'sugg_comp_excel',
            static::SUGG_COMP_PRINT => 'sugg_comp_print',
            static::SETTINGS_SHOW => 'settings_show',
            static::SETTINGS_ADD => 'settings_add',
            static::SETTINGS_UPDATE => 'settings_update',
            static::SETTINGS_DELETE => 'settings_delete',
            static::TERMS_CONDITIONS_SHOW => 'terms_conditions_show',
            static::TERMS_CONDITIONS_ADD => 'terms_conditions_add',
            static::TERMS_CONDITIONS_UPDATE => 'terms_conditions_update',
            static::PRIVACY_POLICY_SHOW => 'privacy_policy_show',
            static::PRIVACY_POLICY_ADD => 'privacy_policy_add',
            static::PRIVACY_POLICY_UPDATE => 'privacy_policy_update',
            static::PRIVACY_POLICY_DELETE => 'privacy_policy_delete',
            static::WHO_US_SHOW => 'who_us_show',
            static::WHO_US_UPDATE => 'who_us_update',
            static::CONNECTION_SHOW => 'connection_show',
            static::CONNECTION_ADD => 'connection_add',
            static::CONNECTION_UPDATE => 'connection_update',
            static::CONNECTION_DELETE => 'connection_delete',
            static::CONNECTION_EXCEL => 'connection_excel',
            static::CONNECTION_PRINT => 'connection_print',
        };
    }

    // functions to get grouped permissions
    public static function dashboardPermissions(): array
    {
        return [
            static::DASHBOARD_SHOW->value,
        ];
    }

    public static function adminsPermissions(): array
    {
        return [
            static::ADMINS_SHOW->value,
            static::ADMINS_ADD->value,
            static::ADMINS_UPDATE->value,
            static::ADMINS_DELETE->value,
        ];
    }

    public static function permissionsPermissions(): array
    {
        return [
            static::PERMISSIONS_ADD->value,
            static::PERMISSIONS_UPDATE->value,
        ];
    }

    public static function usersPermissions(): array
    {
        return [
            static::USERS_SHOW->value,
            static::USERS_ADD->value,
            static::USERS_UPDATE->value,
            static::USERS_DELETE->value,
        ];
    }

    public static function servicesPermissions(): array
    {
        return [
            static::SERVICES_SHOW->value,
            static::SERVICES_ADD->value,
            static::SERVICES_UPDATE->value,
            static::SERVICES_DELETE->value,
        ];
    }

    public static function ordersPermissions(): array
    {
        return [
            static::ORDERS_SHOW->value,
            static::ORDERS_ADD->value,
            static::ORDERS_UPDATE->value,
            static::ORDERS_DELETE->value,
            static::ORDERS_EXCEL->value,
            static::ORDERS_PRINT->value,
        ];
    }

    public static function suggCompPermissions(): array
    {
        return [
            static::SUGG_COMP_SHOW->value,
            static::SUGG_COMP_ADD->value,
            static::SUGG_COMP_UPDATE->value,
            static::SUGG_COMP_DELETE->value,
            static::SUGG_COMP_EXCEL->value,
            static::SUGG_COMP_PRINT->value,
        ];
    }

    public static function settingsPermissions(): array
    {
        return [
            static::SETTINGS_SHOW->value,
            static::SETTINGS_ADD->value,
            static::SETTINGS_UPDATE->value,
            static::SETTINGS_DELETE->value,
        ];
    }

    public static function termsConditionsPermissions(): array
    {
        return [
            static::TERMS_CONDITIONS_SHOW->value,
            static::TERMS_CONDITIONS_ADD->value,
            static::TERMS_CONDITIONS_UPDATE->value,
        ];
    }

    public static function privacyPolicyPermissions(): array
    {
        return [
            static::PRIVACY_POLICY_SHOW->value,
            static::PRIVACY_POLICY_ADD->value,
            static::PRIVACY_POLICY_UPDATE->value,
            static::PRIVACY_POLICY_DELETE->value,
        ];
    }

    public static function whoUsPermissions(): array
    {
        return [
            static::WHO_US_SHOW->value,
            static::WHO_US_UPDATE->value,
        ];
    }

    public static function connectionPermissions(): array
    {
        return [
            static::CONNECTION_SHOW->value,
            static::CONNECTION_ADD->value,
            static::CONNECTION_UPDATE->value,
            static::CONNECTION_DELETE->value,
            static::CONNECTION_EXCEL->value,
            static::CONNECTION_PRINT->value,
        ];
    }
}
