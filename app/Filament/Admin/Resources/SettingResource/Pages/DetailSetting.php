<?php

namespace App\Filament\Admin\Resources\SettingResource\Pages;

use App\Filament\Admin\Resources\SettingResource;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;

class DetailSetting extends Page
{
    protected static string $resource = SettingResource::class;

    protected static string $view = 'filament.admin.resources.setting-resource.pages.detail-setting';

    /**
     * @dev @venixplataformas
     * @param Model $record
     * @return bool
     */
    public static function canView(Model $record): bool
    {
        return auth()->user()->hasRole('admin');
    }
}
