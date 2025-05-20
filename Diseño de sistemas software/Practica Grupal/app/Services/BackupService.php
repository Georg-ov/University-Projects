<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;

class BackupService
{
    public function createBackup()
    {
        try {
            // Ejecutar el comando de Artisan para crear una copia de seguridad
            Artisan::call('backup:run');

            return [
                'status' => 'success',
                'message' => 'Backup created successfully.'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'An error occurred while creating the backup: ' . $e->getMessage()
            ];
        }
    }
}
