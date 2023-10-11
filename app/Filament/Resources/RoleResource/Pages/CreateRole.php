<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    //EA 11 Oct 2023 - Redirect to list page after submission
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
