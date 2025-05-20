<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\BackupService;

class BackupController extends Controller
{
    protected $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    public function createBackup()
    {
        $result = $this->backupService->createBackup();

        if ($result['status'] == 'success') {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }
}
