<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class RestoreService
{
    public function restoreBackup($file)
    {
        $backupPath = storage_path("app/laravel/{$file->getClientOriginalName()}");
        $extractPath = storage_path("app/laravel/extracted");

        // Guardar el archivo subido en la ubicación especificada
        $file->storeAs('laravel', $file->getClientOriginalName());

        // Crear el directorio de extracción si no existe
        if (!Storage::disk('local')->exists('laravel/extracted')) {
            Storage::disk('local')->makeDirectory('laravel/extracted');
        }

        // Descomprimir el archivo de copia de seguridad
        $zip = new ZipArchive;
        if ($zip->open($backupPath) === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();
        } else {
            return [
                'status' => 'error',
                'message' => 'Failed to unzip the backup file.'
            ];
        }

        // Encontrar el archivo SQL en la carpeta db-dumps
        $sqlFile = glob($extractPath . '/db-dumps/*.sql');
        if (empty($sqlFile)) {
            return [
                'status' => 'error',
                'message' => 'No SQL file found in the db-dumps folder of the backup archive.'
            ];
        }

        try {
            // Poner la aplicación en modo de mantenimiento
            Artisan::call('down');

            // Restaurar la base de datos desde el archivo de copia de seguridad
            $command = sprintf(
                'mysql -u%s -p%s %s < %s',
                env('DB_USERNAME'),
                env('DB_PASSWORD'),
                env('DB_DATABASE'),
                $sqlFile[0]
            );
            exec($command);

            // Sacar la aplicación del modo de mantenimiento
            Artisan::call('up');

            // Limpiar los archivos extraídos
            Storage::disk('local')->deleteDirectory('laravel/extracted');

            return [
                'status' => 'success',
                'message' => 'Backup restored successfully.'
            ];
        } catch (\Exception $e) {
            // Sacar la aplicación del modo de mantenimiento incluso si falla la restauración
            Artisan::call('up');

            // Limpiar los archivos extraídos
            Storage::disk('local')->deleteDirectory('laravel/extracted');

            return [
                'status' => 'error',
                'message' => 'An error occurred while restoring the backup: ' . $e->getMessage()
            ];
        }
    }
}
