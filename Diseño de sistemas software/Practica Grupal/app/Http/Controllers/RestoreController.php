<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\RestoreService;
use Illuminate\Http\Request;

class RestoreController extends Controller
{
    protected $restoreService;

    public function __construct(RestoreService $restoreService)
    {
        $this->restoreService = $restoreService;
    }

    public function showRestoreForm()
    {
        return view('restore');
    }

    public function restoreBackup(Request $request)
    {
        $validatedData = $request->validate([
            'backup_file' => 'required|file|mimes:zip',
        ]);

        $file = $request->file('backup_file');
        $result = $this->restoreService->restoreBackup($file);

        if ($result['status'] == 'success') {
            return redirect()->route('home')->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }
}
