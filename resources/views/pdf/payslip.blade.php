<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip - {{ $employee->name ?? 'Employee' }} - {{ $month }} {{ $year }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #006C35;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #006C35;
            margin-bottom: 5px;
        }
        
        .company-info {
            font-size: 10px;
            color: #666;
        }
        
        .payslip-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
            color: #006C35;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            width: 30%;
            background: #f5f5f5;
            padding: 8px;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
        }
        
        .info-value {
            display: table-cell;
            width: 70%;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        
        .section {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            background: #006C35;
            color: white;
            padding: 8px 12px;
            margin-bottom: 0;
        }
        
        .earnings-table, .deductions-table, .net-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .earnings-table th, .deductions-table th {
            background: #f5f5f5;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }
        
        .earnings-table td, .deductions-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        
        .earnings-table td:last-child, .deductions-table td:last-child {
            text-align: right;
        }
        
        .amount {
            font-family: 'Courier New', monospace;
            text-align: right;
        }
        
        .positive {
            color: #006C35;
        }
        
        .negative {
            color: #dc2626;
        }
        
        .net-table {
            margin-top: 15px;
        }
        
        .net-table th {
            background: #006C35;
            color: white;
            padding: 12px;
            text-align: right;
            font-size: 14px;
        }
        
        .net-table td {
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            text-align: right;
            border: 2px solid #006C35;
        }
        
        .net-salary {
            color: #006C35;
            font-size: 20px;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
        
        .signature-section {
            margin-top: 50px;
            display: table;
            width: 100%;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 20px;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 5px;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(0, 108, 53, 0.05);
            pointer-events: none;
            z-index: -1;
        }
        
        @page {
            margin: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">{{ $company['name'] }}</div>
        <div class="company-info">
            {{ $company['address'] }} | {{ $company['phone'] }} | {{ $company['email'] }}<br>
            {{ $company['cr'] }} | {{ $company['vat'] }}
        </div>
    </div>
    
    <div class="payslip-title">PAYSLIP</div>
    
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Employee Name</div>
            <div class="info-value">{{ $employee->name ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Employee ID</div>
            <div class="info-value">{{ $employee->employee_id ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Department</div>
            <div class="info-value">{{ $employee->department ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Designation</div>
            <div class="info-value">{{ $employee->designation ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Pay Period</div>
            <div class="info-value">{{ $month }} {{ $year }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Pay Date</div>
            <div class="info-value">{{ $payroll->pay_date ? date('d M Y', strtotime($payroll->pay_date)) : 'N/A' }}</div>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">EARNINGS</div>
        <table class="earnings-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="width: 120px; text-align: right;">Amount (SAR)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Basic Salary</td>
                    <td class="amount positive">{{ number_format($payroll->basic_salary ?? 0, 2) }}</td>
                </tr>
                @if($payroll->housing_allowance > 0)
                <tr>
                    <td>Housing Allowance</td>
                    <td class="amount positive">{{ number_format($payroll->housing_allowance, 2) }}</td>
                </tr>
                @endif
                @if($payroll->transport_allowance > 0)
                <tr>
                    <td>Transport Allowance</td>
                    <td class="amount positive">{{ number_format($payroll->transport_allowance, 2) }}</td>
                </tr>
                @endif
                @if($payroll->food_allowance > 0)
                <tr>
                    <td>Food Allowance</td>
                    <td class="amount positive">{{ number_format($payroll->food_allowance, 2) }}</td>
                </tr>
                @endif
                @if($payroll->other_allowance > 0)
                <tr>
                    <td>Other Allowance</td>
                    <td class="amount positive">{{ number_format($payroll->other_allowance, 2) }}</td>
                </tr>
                @endif
                @if($payroll->overtime_amount > 0)
                <tr>
                    <td>Overtime ({{ $payroll->overtime_hours ?? 0 }} hrs)</td>
                    <td class="amount positive">{{ number_format($payroll->overtime_amount, 2) }}</td>
                </tr>
                @endif
                @if($payroll->bonus > 0)
                <tr>
                    <td>Bonus</td>
                    <td class="amount positive">{{ number_format($payroll->bonus, 2) }}</td>
                </tr>
                @endif
                <tr style="background: #f5f5f5; font-weight: bold;">
                    <td>Gross Salary</td>
                    <td class="amount positive">{{ number_format($payroll->gross_salary ?? 0, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="section">
        <div class="section-title">DEDUCTIONS</div>
        <table class="deductions-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="width: 120px; text-align: right;">Amount (SAR)</th>
                </tr>
            </thead>
            <tbody>
                @if($payroll->gosi_deduction > 0)
                <tr>
                    <td>GOSI (Employee Share)</td>
                    <td class="amount negative">-{{ number_format($payroll->gosi_deduction, 2) }}</td>
                </tr>
                @endif
                @if($payroll->health_insurance > 0)
                <tr>
                    <td>Health Insurance</td>
                    <td class="amount negative">-{{ number_format($payroll->health_insurance, 2) }}</td>
                </tr>
                @endif
                @if($payroll->absence_deduction > 0)
                <tr>
                    <td>Absence Deduction ({{ $payroll->absence_days ?? 0 }} days)</td>
                    <td class="amount negative">-{{ number_format($payroll->absence_deduction, 2) }}</td>
                </tr>
                @endif
                @if($payroll->loan_deduction > 0)
                <tr>
                    <td>Loan Installment</td>
                    <td class="amount negative">-{{ number_format($payroll->loan_deduction, 2) }}</td>
                </tr>
                @endif
                @if($payroll->other_deduction > 0)
                <tr>
                    <td>Other Deductions</td>
                    <td class="amount negative">-{{ number_format($payroll->other_deduction, 2) }}</td>
                </tr>
                @endif
                <tr style="background: #f5f5f5; font-weight: bold;">
                    <td>Total Deductions</td>
                    <td class="amount negative">-{{ number_format($payroll->total_deductions ?? 0, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="section">
        <table class="net-table">
            <tr>
                <th style="width: 70%;">NET SALARY</th>
                <td class="net-salary">{{ number_format($payroll->net_salary ?? 0, 2) }} SAR</td>
            </tr>
        </table>
    </div>
    
    @if($payroll->loan_balance > 0)
    <div class="section">
        <p style="font-size: 10px; color: #666;">
            <strong>Outstanding Loan Balance:</strong> {{ number_format($payroll->loan_balance, 2) }} SAR
        </p>
    </div>
    @endif
    
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line">Employee Signature</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">Authorized Signature</div>
        </div>
    </div>
    
    <div class="footer">
        <p>This is a computer-generated document. No signature required.</p>
        <p>Generated on: {{ date('d M Y H:i:s') }} | {{ $company['name'] }} | Page 1 of 1</p>
    </div>
</body>
</html>
