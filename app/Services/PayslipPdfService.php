<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Payroll;
use App\Models\Employee;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class PayslipPdfService
{
    /**
     * Generate payslip PDF for an employee
     */
    public function generatePayslip(Payroll $payroll): string
    {
        $employee = $payroll->employee;
        $company = $this->getCompanyInfo();
        
        $data = [
            'payroll' => $payroll,
            'employee' => $employee,
            'company' => $company,
            'month' => $this->formatMonth($payroll->payroll_month),
            'year' => $payroll->payroll_year,
        ];

        $pdf = Pdf::loadView('pdf.payslip', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = sprintf(
            'payslip_%s_%d_%s.pdf',
            $employee->employee_id ?? 'EMP',
            $payroll->payroll_year,
            $payroll->payroll_month
        );

        // Store in storage
        $path = 'payslips/' . ($employee->id ?? 'unknown') . '/' . $filename;
        \Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }

    /**
     * Get company information from settings
     */
    private function getCompanyInfo(): array
    {
        return [
            'name' => setting('site_name', 'Bin Mishal Travels'),
            'logo' => setting('logo'),
            'address' => setting('company_address', 'Riyadh, Saudi Arabia'),
            'phone' => setting('contact_phone', '+966 XX XXX XXXX'),
            'email' => setting('contact_email', 'info@binmishal.com'),
            'cr' => setting('company_cr', 'CR No: XXXXXX'),
            'vat' => setting('vat_number', 'VAT: XXXXXXXXX'),
        ];
    }

    /**
     * Format month number to name
     */
    private function formatMonth(string $month): string
    {
        $months = [
            '01' => 'January', '02' => 'February', '03' => 'March',
            '04' => 'April', '05' => 'May', '06' => 'June',
            '07' => 'July', '08' => 'August', '09' => 'September',
            '10' => 'October', '11' => 'November', '12' => 'December',
        ];
        
        return $months[$month] ?? $month;
    }

    /**
     * Get payslip HTML for preview
     */
    public function getPayslipHtml(Payroll $payroll): string
    {
        $employee = $payroll->employee;
        $company = $this->getCompanyInfo();
        
        return View::make('pdf.payslip', [
            'payroll' => $payroll,
            'employee' => $employee,
            'company' => $company,
            'month' => $this->formatMonth($payroll->payroll_month),
            'year' => $payroll->payroll_year,
            'preview' => true,
        ])->render();
    }
}
