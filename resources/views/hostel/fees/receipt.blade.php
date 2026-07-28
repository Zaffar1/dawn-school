<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Fee Receipt - #H-{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            padding: 40px 0;
        }
        .receipt-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            max-width: 700px;
            margin: 0 auto;
            padding: 40px;
            position: relative;
        }
        .receipt-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #1e3a8a 0%, #3b82f6 100%);
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
        }
        .receipt-header {
            border-bottom: 2px dashed #e2e8f0;
            padding-bottom: 25px;
            margin-bottom: 30px;
        }
        .school-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            color: #1e3a8a;
            margin-bottom: 5px;
        }
        .receipt-title {
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 700;
            color: #475569;
            font-size: 1.15rem;
        }
        .receipt-info-value {
            font-weight: 600;
            color: #0f172a;
        }
        .table-receipt th {
            font-weight: 600;
            color: #475569;
            border-bottom: 2px solid #cbd5e1;
        }
        .table-receipt td {
            padding: 15px 8px;
            color: #334155;
        }
        .amount-box {
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }
        .amount-large {
            font-size: 1.8rem;
            font-weight: 800;
            color: #1e3a8a;
        }
        .signature-line {
            border-top: 1px solid #cbd5e1;
            margin-top: 60px;
            padding-top: 10px;
            text-align: center;
            font-size: 0.85rem;
            font-weight: 500;
            color: #64748b;
        }
        /* Print rules */
        @media print {
            body {
                background-color: #ffffff;
                padding: 0;
            }
            .receipt-card {
                box-shadow: none;
                border: none;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .receipt-card::before {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="container text-center mb-4 no-print">
    <button onclick="window.print()" class="btn btn-primary px-4 py-2 me-2">
        <i class="fa-solid fa-print me-2"></i>Print Receipt
    </button>
    <button onclick="window.close()" class="btn btn-outline-secondary px-4 py-2">
        <i class="fa-solid fa-xmark me-2"></i>Close Window
    </button>
</div>

<div class="receipt-card">
    <!-- Header -->
    <div class="receipt-header text-center">
        <h2 class="school-title">Sukkur Hostel</h2>
        <p class="text-muted small mb-3"><i class="fa-solid fa-hotel me-1 text-primary"></i>Official Accommodation Receipt</p>
        <span class="badge bg-primary px-3 py-2 receipt-title">Hostel Fee Receipt</span>
    </div>

    <!-- Info Info -->
    <div class="row mb-4 g-3">
        <div class="col-sm-6">
            <div class="text-muted small">Receipt Number:</div>
            <div class="receipt-info-value">#H-{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</div>
        </div>
        <div class="col-sm-6 text-sm-end">
            <div class="text-muted small">Payment Date:</div>
            <div class="receipt-info-value">{{ $payment->date->format('d-M-Y') }}</div>
        </div>
        <div class="col-sm-6">
            <div class="text-muted small">Billing Month:</div>
            <div class="receipt-info-value">{{ \Carbon\Carbon::parse($payment->billing_month)->format('F Y') }}</div>
        </div>
        <div class="col-sm-6 text-sm-end">
            <div class="text-muted small">Payment Method:</div>
            <div class="receipt-info-value">{{ $payment->payment_method }}</div>
        </div>
    </div>

    <!-- Student/Staff Info -->
    <div class="card bg-light border-0 mb-4 rounded-3">
        <div class="card-body p-4">
            <h6 class="text-primary fw-bold mb-3"><i class="fa-solid fa-user me-2"></i>Student / Staff Information</h6>
            <div class="row g-2 small">
                <div class="col-sm-4 text-muted">Full Name:</div>
                <div class="col-sm-8 fw-semibold text-dark">{{ $payment->resident->name }}</div>
                
                <div class="col-sm-4 text-muted">Person Type:</div>
                <div class="col-sm-8">
                    @if($payment->resident->resident_type === 'student' || $payment->resident->resident_type === 'resident')
                        Hostel Student
                    @else
                        Hostel Staff
                    @endif
                </div>

                <div class="col-sm-4 text-muted">Room Assignment:</div>
                <div class="col-sm-8 fw-bold text-dark">Room # {{ $payment->resident->room_number }}</div>

                <div class="col-sm-4 text-muted">Contact Phone:</div>
                <div class="col-sm-8">{{ $payment->resident->phone ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Ledger table / charges breakdown -->
    <div class="table-responsive">
        <table class="table table-receipt table-borderless">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-end">Due Amount</th>
                    <th class="text-end">Amount Paid</th>
                    <th class="text-end">Arrears</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="fw-semibold">Hostel Monthly Fee Collection</div>
                        <div class="text-muted small">Charges collected for the month of {{ \Carbon\Carbon::parse($payment->billing_month)->format('F Y') }}</div>
                        @if($payment->reference_no)
                            <div class="text-muted small"><i class="fa-solid fa-receipt me-1"></i>Ref No: {{ $payment->reference_no }}</div>
                        @endif
                    </td>
                    <td class="text-end fw-bold text-secondary">Rs. {{ number_format($payment->due_amount ?? ($payment->resident->monthly_fee ?? 0.00), 2) }}</td>
                    <td class="text-end fw-bold text-success">Rs. {{ number_format($payment->amount, 2) }}</td>
                    <td class="text-end fw-bold {{ ($payment->arrears ?? 0) > 0 ? 'text-danger' : 'text-muted' }}">Rs. {{ number_format($payment->arrears ?? 0.00, 2) }}</td>
                </tr>
                @if($payment->notes)
                    <tr>
                        <td colspan="4" class="text-muted py-1 small">
                            <strong>Remarks:</strong> {{ $payment->notes }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Total box -->
    <div class="amount-box d-flex justify-between align-items-center">
        <div>
            <div class="text-muted fw-semibold small text-uppercase">Total Received Amount</div>
            <div class="small text-muted font-monospace">Rs. {{ number_format($payment->amount, 2) }} Paid in Full</div>
        </div>
        <div class="ms-auto text-end">
            <span class="amount-large">Rs. {{ number_format($payment->amount, 2) }}</span>
        </div>
    </div>

    <!-- Signatures -->
    <div class="row mt-5 pt-3">
        <div class="col-6">
            <div class="signature-line">Student Signature</div>
        </div>
        <div class="col-6">
            <div class="signature-line">Authorized Signature</div>
        </div>
    </div>
</div>

<!-- Auto triggering print dialog -->
<script>
    window.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            window.print();
        }, 500);
    });
</script>
</body>
</html>
