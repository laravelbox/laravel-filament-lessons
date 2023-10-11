<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;

    //EA 11 Oct 2023 - Redirect to list page after submission
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
