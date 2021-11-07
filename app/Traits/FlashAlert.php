<?php

namespace App\Traits;

trait FlashAlert
{
    public function alertCreated($data = "Data successfully created!")
    {
        return [
            'type' => 'success',
            'message' => $data,
        ];
    }

    public function alertUpdated($data = "Data successfully updated")
    {
        return [
            'type' => 'success',
            'message' => $data,
        ];
    }

    public function alertDeleted($data = "Data successfully deleted")
    {
        return [
            'type' => 'success',
            'message' => $data,
        ];
    }

    public function alertNotFound($data = "Data not found")
    {
        return [
            'type' => 'warning',
            'message' => $data
        ];
    }

    public function alertDanger($data = "Something wrong")
    {
        return [
            'type' => 'danger',
            'message' => $data,
        ];
    }

    public function permissionDenied($data = "you don`t have permission to access")
    {
        return [
            'type' => 'danger',
            'message' => $data,
        ];
    }



}
