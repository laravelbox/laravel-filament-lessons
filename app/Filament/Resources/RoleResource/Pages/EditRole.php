<?php

namespace App\Filament\Resources\RoleResource\Pages;

//EA 25 Oct 2023 - Customise notification
use Filament\Pages\Actions;
use App\Filament\Resources\RoleResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    //EA 11 Oct 2023 - Redirect to list page after submission
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    //EA 25 Oct 2023 - Customise notification
   /*
    protected function getSavedNotificationTitle(): ?string
    {
        return 'A role has been updated2 successfully.';
    }
    */

    //EA 25 Oct 2023 - Customise notification
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Role updated')
            ->body('The role has been updated successfully.');
    }


}
