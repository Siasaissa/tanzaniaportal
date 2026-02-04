<!DOCTYPE html>
<html>
<head>
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body { 
            font-family: 'Helvetica Neue', Arial, sans-serif; 
            margin: 0; 
            padding: 20px; 
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 40px; 
            border-bottom: 3px solid #2c3e50; 
            padding-bottom: 20px;
        }
        .header h1 {
            color: #2c3e50;
            margin: 0 0 10px 0;
            font-size: 28px;
        }
        .header h2 {
            color: #3498db;
            margin: 0;
            font-size: 22px;
            font-weight: normal;
        }
        .company-info {
            margin-bottom: 30px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #3498db;
        }
        .company-info h3 {
            margin: 0 0 10px 0;
            color: #2c3e50;
        }
        .invoice-details {
            margin-bottom: 30px;
        }
        .invoice-details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }
        .detail-box {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #e9ecef;
        }
        .detail-box h4 {
            margin: 0 0 10px 0;
            color: #2c3e50;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }
        .section-title {
            background-color: #2c3e50;
            color: white;
            padding: 10px 15px;
            margin: 25px 0 15px 0;
            border-radius: 3px;
            font-weight: bold;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #3498db;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #3498db;
        }
        td {
            border: 1px solid #dee2e6;
            padding: 10px 8px;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #f1f8ff;
        }
        .total-section {
            margin-top: 30px;
            margin-left: auto;
            width: 300px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .total-row.total {
            font-size: 18px;
            font-weight: bold;
            border-top: 2px solid #2c3e50;
            border-bottom: none;
            margin-top: 10px;
            padding-top: 15px;
            color: #2c3e50;
        }
        .total-row.paid {
            color: #27ae60;
            font-weight: bold;
        }
        .total-row.balance {
            color: #e74c3c;
            font-weight: bold;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-right: 5px;
        }
        .status-paid { background-color: #d5edda; color: #155724; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-overdue { background-color: #f8d7da; color: #721c24; }
        .status-draft { background-color: #e2e3e5; color: #383d41; }
        .notes-section {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #3498db;
        }
        .notes-section h4 {
            margin: 0 0 10px 0;
            color: #2c3e50;
        }
        .signature-section {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #dee2e6;
        }
        .signature-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            margin-top: 30px;
        }
        .signature-box {
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 80px;
            padding-top: 10px;
        }
        .footer {
            margin-top: 60px;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
        }
        .payment-info {
            background-color: #e8f4fd;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #3498db;
        }
        .page-break {
            page-break-before: always;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .mb-0 { margin-bottom: 0; }
        .mt-0 { margin-top: 0; }
        .mb-10 { margin-bottom: 10px; }
        .mb-20 { margin-bottom: 20px; }
        .mb-30 { margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>INVOICE</h1>
            <h2>{{ $invoice->invoice_number }}</h2>
            <div style="margin-top: 10px;">
                <span class="status-badge status-{{ $invoice->payment_status }}">
                    {{ strtoupper($invoice->payment_status) }}
                </span>
                <span class="status-badge">
                    {{ strtoupper($invoice->status) }}
                </span>
                @if($invoice->is_overdue)
                <span class="status-badge status-overdue">
                    OVERDUE: {{ $invoice->days_overdue }} DAYS
                </span>
                @endif
            </div>
        </div>
        
        <div class="company-info">
            <h3>{{ $invoice->company->name }}</h3>
            <p class="mb-0">{{ $company->client_address ?? 'Company Address' }}</p>
            <p class="mb-0">Phone: {{ $invoice->company->phone ?? 'N/A' }} | Email: {{ $invoice->company->email ?? 'N/A' }}</p>
            @if(Auth::user()->vat_number)
            <p class="mb-0">VAT Number: {{ Auth::user()->vat_number }}</p>
            @endif
        </div>
        
        <div class="invoice-details">
            <div class="invoice-details-grid">
                <div class="detail-box">
                    <h4>Bill To:</h4>
                    <p><strong>{{ $invoice->client_name }}</strong></p>
                    @if($invoice->client_email)
                    <p class="mb-0">Email: {{ $invoice->client_email }}</p>
                    @endif
                    @if($invoice->client_phone)
                    <p class="mb-0">Phone: {{ $invoice->client_phone }}</p>
                    @endif
                    @if($invoice->client_address)
                    <p class="mb-0" style="white-space: pre-line;">{{ $invoice->client_address }}</p>
                    @endif
                </div>
                
                <div class="detail-box">
                    <h4>Invoice Details:</h4>
                    <p class="mb-0"><strong>Invoice Date:</strong> {{ $invoice->invoice_date->format('F d, Y') }}</p>
                    <p class="mb-0"><strong>Due Date:</strong> {{ $invoice->due_date->format('F d, Y') }}</p>
                    @php
                        $deliveryNote = optional($invoice->deliveryNote);
                    @endphp
                    <p class="mb-0"><strong>Delivery Note:</strong> {{ $deliveryNote->delivery_note_number ?? 'N/A' }}</p>
                    @if($deliveryNote && $deliveryNote->delivery_date)
                    <p class="mb-0"><strong>Delivery Date:</strong> {{ $deliveryNote->delivery_date->format('F d, Y') }}</p>
                    @endif
                </div>
            </div>
            
            @if($invoice->payment_method || $invoice->transaction_reference)
            <div class="payment-info">
                <h4 style="margin-top: 0;">Payment Information:</h4>
                @if($invoice->payment_method)
                <p class="mb-0"><strong>Payment Method:</strong> {{ ucwords(str_replace('_', ' ', $invoice->payment_method)) }}</p>
                @endif
                @if($invoice->transaction_reference)
                <p class="mb-0"><strong>Transaction Reference:</strong> {{ $invoice->transaction_reference }}</p>
                @endif
                @if($invoice->last_payment_date)
                <p class="mb-0"><strong>Last Payment Date:</strong> {{ $invoice->last_payment_date->format('F d, Y') }}</p>
                @endif
            </div>
            @endif
        </div>
        
        <div class="section-title">Invoice Items</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 45%;">Description</th>
                    <th style="width: 15%; text-align: center;">Quantity</th>
                    <th style="width: 20%; text-align: right;">Unit Price (Tsh)</th>
                    <th style="width: 20%; text-align: right;">Amount (Tsh)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->formatted_items as $item)
                <tr>
                    <td>{{ $item['description'] ?? '' }}</td>
                    <td style="text-align: center;">{{ number_format($item['quantity'] ?? 0) }}</td>
                    <td style="text-align: right;">{{ number_format($item['price'] ?? 0, 2) }}</td>
                    <td style="text-align: right;">{{ number_format(($item['quantity'] ?? 0) * ($item['price'] ?? 0), 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="total-section">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>Tsh {{ number_format($invoice->subtotal, 2) }}</span>
            </div>
            
            @if($invoice->tax > 0)
            <div class="total-row">
                <span>Tax ({{ $invoice->tax_rate }}%):</span>
                <span>Tsh {{ number_format($invoice->tax, 2) }}</span>
            </div>
            @endif
            
            @if($invoice->discount > 0)
            <div class="total-row">
                <span>Discount:</span>
                <span>Tsh -{{ number_format($invoice->discount, 2) }}</span>
            </div>
            @endif
            
            <div class="total-row total">
                <span>INVOICE TOTAL:</span>
                <span>Tsh {{ number_format($invoice->total, 2) }}</span>
            </div>
            
            <div class="total-row paid">
                <span>Amount Paid:</span>
                <span>Tsh {{ number_format($invoice->amount_paid, 2) }}</span>
            </div>
            
            <div class="total-row balance">
                <span>BALANCE DUE:</span>
                <span>Tsh {{ number_format($invoice->balance, 2) }}</span>
            </div>
        </div>
        
        @if($invoice->notes || $invoice->terms)
        <div class="section-title">Additional Information</div>
        
        @if($invoice->notes)
        <div class="notes-section mb-20">
            <h4>Notes:</h4>
            <div style="white-space: pre-line;">{{ $invoice->notes }}</div>
        </div>
        @endif
        
        @if($invoice->terms)
        <div class="notes-section">
            <h4>Terms & Conditions:</h4>
            <div style="white-space: pre-line;">{{ $invoice->terms }}</div>
        </div>
        @endif
        @endif

        
        <div class="footer">
            <p>Invoice generated on: {{ now()->format('F d, Y H:i:s') }}</p>
            <p>This is a computer-generated invoice. No signature required.</p>
            <p>{{ $invoice->company->name ?? 'Your Company' }} | {{ $company->address ?? '' }}</p>
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>
</html>