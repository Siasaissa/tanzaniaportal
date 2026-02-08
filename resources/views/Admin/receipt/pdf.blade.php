<!DOCTYPE html>
<html>
<head>
    <title>Goods Receipt {{ $receipt->receipt_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .document-title {
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .document-number {
            font-size: 14px;
            color: #555;
        }
        
        .info-section {
            margin-bottom: 20px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            width: 120px;
        }
        
        .info-value {
            flex: 1;
        }
        
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .status-verified { background: #d4edda; color: #155724; }
        .status-completed { background: #d1ecf1; color: #0c5460; }
        .status-draft { background: #f8f9fa; color: #6c757d; }
        .status-partial { background: #fff3cd; color: #856404; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th {
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        
        .total-row td {
            text-align: right;
        }
        
        .grand-total {
            font-size: 12px;
            color: #28a745;
        }
        
        .notes-section {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 3px solid #007bff;
        }
        
        .notes-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .signatures {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature-box {
            text-align: center;
            width: 30%;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin: 30px 0 5px 0;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="document-title">GOODS RECEIPT NOTE</div>
        <div class="document-number">GRN: {{ $receipt->receipt_number }}</div>
        <div>Date: {{ now()->format('d/m/Y H:i') }}</div>
    </div>
    
    <!-- Basic Information -->
    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Receipt Date:</div>
            <div class="info-value">{{ $receipt->receipt_date->format('d/m/Y') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">PO Number:</div>
            <div class="info-value">{{ $receipt->purchaseOrder->po_number ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Supplier:</div>
            <div class="info-value">{{ $receipt->supplier_name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Status:</div>
            <div class="info-value">
                <span class="status-badge status-{{ $receipt->status }}">
                    {{ strtoupper($receipt->status) }}
                </span>
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Delivery Note:</div>
            <div class="info-value">{{ $receipt->delivery_note_number ?? 'N/A' }}</div>
        </div>
    </div>
    
    <!-- Items Table -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Description</th>
                <th class="text-right">Ordered</th>
                <th class="text-right">Received</th>
                <th>Unit</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Decode items from JSON or use as array
                $items = is_string($receipt->items) ? json_decode($receipt->items, true) : $receipt->items;
                $items = is_array($items) ? $items : [];
            @endphp
            
            @if(count($items) > 0)
                @foreach($items as $index => $item)
                @php
                    $item = (object)$item; // Convert array to object for easy access
                    $quantityReceived = $item->quantity_received ?? $item['quantity_received'] ?? 0;
                    $price = $item->price ?? $item['price'] ?? 0;
                    $total = $quantityReceived * $price;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->description ?? $item['description'] ?? 'N/A' }}</td>
                    <td class="text-right">{{ number_format($item->quantity_ordered ?? $item['quantity_ordered'] ?? 0) }}</td>
                    <td class="text-right">{{ number_format($quantityReceived) }}</td>
                    <td>{{ $item->unit ?? $item['unit'] ?? 'pcs' }}</td>
                    <td class="text-right">Tsh {{ number_format($price, 2) }}</td>
                    <td class="text-right">Tsh {{ number_format($total, 2) }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center">No items received</td>
                </tr>
            @endif
            
            <!-- Totals -->
            <tr class="total-row">
                <td colspan="2">Total Items</td>
                <td class="text-right">{{ $receipt->total_items_received ?? 0 }}</td>
                <td colspan="2">Total Quantity</td>
                <td colspan="2" class="text-right">{{ number_format($receipt->total_quantity_received ?? 0) }}</td>
            </tr>
            <tr class="total-row grand-total">
                <td colspan="6">GRAND TOTAL</td>
                <td class="text-right">Tsh {{ number_format($receipt->total_amount ?? 0, 2) }}</td>
            </tr>
        </tbody>
    </table>
    
    <!-- Notes -->
    @if($receipt->quality_notes || $receipt->notes || $receipt->return_reason)
    <div class="notes-section">
        <div class="notes-title">NOTES:</div>
        @if($receipt->quality_notes)
        <div><strong>Quality:</strong> {{ $receipt->quality_notes }}</div>
        @endif
        @if($receipt->notes)
        <div><strong>Remarks:</strong> {{ $receipt->notes }}</div>
        @endif
        @if($receipt->return_reason)
        <div><strong>Return Reason:</strong> {{ $receipt->return_reason }}</div>
        @endif
    </div>
    @endif
    
    <!-- Footer -->
    <div class="footer">
        <div>Quotation Date: {{ $receipt->date }}</div>
        <div>prepared by <strong>{{ $receipt->company->name ?? Auth::user()->name }}</strong> Company Limited</div>
        <div>Thank you for your business!</div>
    </div>
</body>
</html>