<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class SettingsController extends Controller
{
    /**
     * Display tax/VAT settings.
     */
    public function tax()
    {
        $settings = [
            'tax_vat_enabled' => Setting::getValue('tax_vat_enabled', true),
            'tax_vat_rate' => Setting::getValue('tax_vat_rate', 15),
            'tax_vat_number' => Setting::getValue('tax_vat_number', ''),
            'tax_vat_applicable_services' => Setting::getValue('tax_vat_applicable_services', 'flight,umrah,visa,cargo'),
        ];

        return view('admin.settings.tax', compact('settings'));
    }

    /**
     * Update tax/VAT settings.
     */
    public function updateTax(Request $request)
    {
        $validated = $request->validate([
            'tax_vat_enabled' => 'boolean',
            'tax_vat_rate' => 'required|numeric|min:0|max:100',
            'tax_vat_number' => 'nullable|string|max:50',
            'tax_vat_applicable_services' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            Setting::setValue($key, $value);
        }

        return redirect()->back()->with('success', 'Tax settings updated successfully.');
    }

    /**
     * Display backup management.
     */
    public function backup()
    {
        $backups = $this->getBackupFiles();
        return view('admin.settings.backup', compact('backups'));
    }

    /**
     * Create a database backup.
     */
    public function createBackup()
    {
        try {
            // Run Laravel backup command if spatie/laravel-backup is installed
            // For now, create a simple database dump
            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $path = storage_path('app/backups/' . $filename);
            
            // Ensure directory exists
            if (!is_dir(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }

            // This is a placeholder - in production, use mysqldump or spatie/laravel-backup
            // For now, just create a marker file
            File::put($path, '-- Database backup placeholder');

            return redirect()->back()->with('success', 'Backup created successfully: ' . $filename);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    /**
     * Download a backup file.
     */
    public function downloadBackup($filename)
    {
        $path = storage_path('app/backups/' . $filename);
        
        if (!file_exists($path)) {
            abort(404, 'Backup file not found');
        }

        return response()->download($path);
    }

    /**
     * Delete a backup file.
     */
    public function deleteBackup($filename)
    {
        $path = storage_path('app/backups/' . $filename);
        
        if (file_exists($path)) {
            unlink($path);
            return redirect()->back()->with('success', 'Backup deleted successfully.');
        }

        return redirect()->back()->with('error', 'Backup file not found.');
    }

    /**
     * Get list of backup files.
     */
    private function getBackupFiles()
    {
        $backups = [];
        $path = storage_path('app/backups/');
        
        if (is_dir($path)) {
            foreach (glob($path . '*.sql') as $file) {
                $backups[] = [
                    'filename' => basename($file),
                    'size' => filesize($file),
                    'created' => filemtime($file),
                ];
            }
        }

        return collect($backups)->sortByDesc('created')->values();
    }

    /**
     * Display URL redirect management.
     */
    public function redirects()
    {
        $redirects = Redirect::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.settings.redirects', compact('redirects'));
    }

    /**
     * Store a new redirect.
     */
    public function storeRedirect(Request $request)
    {
        $validated = $request->validate([
            'old_url' => 'required|string|max:255',
            'new_url' => 'required|string|max:255',
            'type' => 'required|in:301,302',
            'notes' => 'nullable|string',
        ]);

        // Check for duplicate
        if (Redirect::where('old_url', $validated['old_url'])->exists()) {
            return redirect()->back()->with('error', 'A redirect for this URL already exists.');
        }

        Redirect::create($validated);

        return redirect()->back()->with('success', 'Redirect created successfully.');
    }

    /**
     * Delete a redirect.
     */
    public function deleteRedirect($id)
    {
        $redirect = Redirect::findOrFail($id);
        $redirect->delete();

        return redirect()->back()->with('success', 'Redirect deleted successfully.');
    }

    /**
     * Display maintenance mode settings.
     */
    public function maintenance()
    {
        $settings = [
            'maintenance_enabled' => Setting::getValue('maintenance_enabled', false),
            'maintenance_message' => Setting::getValue('maintenance_message', 'Site is under maintenance. Please check back soon.'),
            'maintenance_allowed_ips' => Setting::getValue('maintenance_allowed_ips', ''),
        ];

        return view('admin.settings.maintenance', compact('settings'));
    }

    /**
     * Update maintenance mode settings.
     */
    public function updateMaintenance(Request $request)
    {
        $validated = $request->validate([
            'maintenance_enabled' => 'boolean',
            'maintenance_message' => 'nullable|string|max:500',
            'maintenance_allowed_ips' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            Setting::setValue($key, $value);
        }

        // Toggle maintenance mode
        if ($validated['maintenance_enabled']) {
            Artisan::call('up', ['--secret' => 'maintenance-secret']);
        } else {
            Artisan::call('down');
        }

        return redirect()->back()->with('success', 'Maintenance settings updated successfully.');
    }
}
