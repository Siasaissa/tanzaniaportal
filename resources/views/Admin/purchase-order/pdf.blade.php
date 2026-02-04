<!DOCTYPE html>
<html>
<head>
    <title>Purchase Order {{ $purchaseOrder->po_number }}</title>
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
        .details-grid {
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
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-draft { background-color: #e2e3e5; color: #383d41; }
        .status-pending_approval { background-color: #fff3cd; color: #856404; }
        .status-approved { background-color: #d1ecf1; color: #0c5460; }
        .status-ordered { background-color: #d1e7dd; color: #0f5132; }
        .status-completed { background-color: #d4edda; color: #155724; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
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
        .text-right {
            text-align: right;
        }
        .mb-0 { margin-bottom: 0; }
        .mt-0 { margin-top: 0; }
        .mb-20 { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>PURCHASE ORDER</h1>
            <h2>{{ $purchaseOrder->po_number }}</h2>
            <div style="margin-top: 10px;">
                <span class="status-badge status-{{ $purchaseOrder->status }}">
                    {{ strtoupper(str_replace('_', ' ', $purchaseOrder->status)) }}
                </span>
            </div>
        </div>
        
        <div class="company-info">
            <h3>{{ $purchaseOrder->company->name ?? Auth::user()->name }}</h3>
            <p class="mb-0">{{ $company->address ?? 'Company Address' }}</p>
            <p class="mb-0">Phone: {{ $purchaseOrder->company->phone ?? 'N/A' }} | Email: {{ $purchaseOrder->company->email ?? Auth::user()->email }}</p>
            
        </div>
        
        <div class="details-grid">
            <div class="detail-box">
                <h4>Supplier Information:</h4>
                <p class="mb-0"><strong>{{ $purchaseOrder->supplier_name }}</strong></p>
                @if($purchaseOrder->supplier_contact_person)
                <p class="mb-0">Contact: {{ $purchaseOrder->supplier_contact_person }}</p>
                @endif
                @if($purchaseOrder->supplier_email)
                <p class="mb-0">Email: {{ $purchaseOrder->supplier_email }}</p>
                @endif
                @if($purchaseOrder->supplier_phone)
                <p class="mb-0">Phone: {{ $purchaseOrder->supplier_phone }}</p>
                @endif
                @if($purchaseOrder->supplier_address)
                <p class="mb-0" style="white-space: pre-line;">{{ $purchaseOrder->supplier_address }}</p>
                @endif
            </div>
            
            <div class="detail-box">
                <h4>Order Details:</h4>
                <p class="mb-0"><strong>PO Date:</strong> {{ $purchaseOrder->po_date->format('F d, Y') }}</p>
                @if($purchaseOrder->expected_delivery_date)
                <p class="mb-0"><strong>Expected Delivery:</strong> {{ $purchaseOrder->expected_delivery_date->format('F d, Y') }}</p>
                @endif
                <p class="mb-0"><strong>Payment Terms:</strong> {{ $purchaseOrder->payment_terms_text }}</p>
                @if($purchaseOrder->delivery_method)
                <p class="mb-0"><strong>Delivery Method:</strong> {{ ucfirst($purchaseOrder->delivery_method) }}</p>
                @endif
                @if($purchaseOrder->approved_at)
                <p class="mb-0"><strong>Approved By:</strong> {{ $purchaseOrder->approver->name ?? 'N/A' }}</p>
                <p class="mb-0"><strong>Approved On:</strong> {{ $purchaseOrder->approved_at->format('F d, Y H:i') }}</p>
                @endif
            </div>
        </div>
        
        @if($purchaseOrder->shipping_address)
        <div class="section-title">Shipping Information</div>
        <div class="detail-box">
            <p class="mb-0"><strong>Shipping Address:</strong></p>
            <div style="white-space: pre-line;">{{ $purchaseOrder->shipping_address }}</div>
            @if($purchaseOrder->shipping_instructions)
            <p class="mb-0 mt-2"><strong>Instructions:</strong> {{ $purchaseOrder->shipping_instructions }}</p>
            @endif
        </div>
        @endif
        
        <div class="section-title">Order Items</div>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th style="text-align: right;">Unit Price (Tsh)</th>
                    <th style="text-align: right;">Amount (Tsh)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchaseOrder->formatted_items as $item)
                <tr>
                    <td>{{ $item['description'] ?? '' }}</td>
                    <td>{{ number_format($item['quantity'] ?? 0) }}</td>
                    <td>{{ $item['unit'] ?? '-' }}</td>
                    <td style="text-align: right;">{{ number_format($item['price'] ?? 0, 2) }}</td>
                    <td style="text-align: right;">{{ number_format(($item['quantity'] ?? 0) * ($item['price'] ?? 0), 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="total-section">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>Tsh {{ number_format($purchaseOrder->subtotal, 2) }}</span>
            </div>
            
            @if($purchaseOrder->tax_amount > 0)
            <div class="total-row">
                <span>Tax ({{ $purchaseOrder->tax_rate }}%):</span>
                <span>Tsh {{ number_format($purchaseOrder->tax_amount, 2) }}</span>
            </div>
            @endif
            
            @if($purchaseOrder->discount > 0)
            <div class="total-row">
                <span>Discount:</span>
                <span>Tsh -{{ number_format($purchaseOrder->discount, 2) }}</span>
            </div>
            @endif
            
            @if($purchaseOrder->shipping_cost > 0)
            <div class="total-row">
                <span>Shipping Cost:</span>
                <span>Tsh {{ number_format($purchaseOrder->shipping_cost, 2) }}</span>
            </div>
            @endif
            
            <div class="total-row total">
                <span>TOTAL AMOUNT:</span>
                <span>Tsh {{ number_format($purchaseOrder->total_amount, 2) }}</span>
            </div>
        </div>
        
        @if($purchaseOrder->notes || $purchaseOrder->terms_conditions)
        <div class="section-title">Additional Information</div>
        
        @if($purchaseOrder->notes)
        <div class="notes-section mb-20">
            <h4>Notes:</h4>
            <div style="white-space: pre-line;">{{ $purchaseOrder->notes }}</div>
        </div>
        @endif
        
        @if($purchaseOrder->terms_conditions)
        <div class="notes-section">
            <h4>Terms & Conditions:</h4>
            <div style="white-space: pre-line;">{{ $purchaseOrder->terms_conditions }}</div>
        </div>
        @endif
        @endif
        
        <div class="footer">
            <p>Purchase Order generated on: {{ now()->format('F d, Y H:i:s') }}</p>
            <p>Prepared by {{ $deliveryNote->company->name ?? Auth::user()->name }} under provider {{ $deliveryNote->company->provider ?? Auth::user()->name}}</p>
            <p>Thank you for your cooperation!</p>
        </div>
    </div>
</body>
</html>