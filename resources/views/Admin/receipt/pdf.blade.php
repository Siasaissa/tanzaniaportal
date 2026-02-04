<!DOCTYPE html>
<html>
<head>
    <title>Goods Receipt {{ $receipt->receipt_number }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            font-size: 12px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .company-info { 
            margin-bottom: 30px; 
        }
        .document-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .info-box {
            flex: 1;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
        }
        th { 
            background-color: #f2f2f2; 
            font-weight: bold;
        }
        .total-row { 
            font-weight: bold; 
        }
        .footer { 
            margin-top: 50px; 
            font-size: 11px;
            text-align: center;
            color: #666;
        }
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            flex: 1;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin: 30px 0 5px 0;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 3px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>GOODS RECEIPT NOTE</h1>
        <h3>{{ $receipt->receipt_number }}</h3>
    </div>
    
    <div class="company-info">
        <h3>{{ config('app.name', 'Your Company') }}</h3>
        <p>123 Business Street, City, Country</p>
        <p>Phone: +255 123 456 789 | Email: info@company.com</p>
    </div>
    
    <div class="document-info">
        <div class="info-box">
            <p><strong>Receipt Date:</strong> {{ $receipt->receipt_date->format('F d, Y') ?? 'N/A' }}</p>
            <p><strong>Purchase Order:</strong> {{ $receipt->purchaseOrder->po_number ?? 'N/A' }}</p>
            <p><strong>Receipt Type:</strong> {{ ucwords(str_replace('_', ' ', $receipt->receipt_type ?? 'N/A')) }}</p>
        </div>
        
        <div class="info-box">
            <p><strong>Supplier:</strong> {{ $receipt->supplier_name ?? 'N/A' }}</p>
            <p><strong>Contact:</strong> {{ $receipt->supplier_contact_person ?? 'N/A' }}</p>
            <p><strong>Phone:</strong> {{ $receipt->supplier_contact_phone ?? 'N/A' }}</p>
        </div>
        
        <div class="info-box">
            <p><strong>Status:</strong> 
                <span class="status-badge" style="
                    background-color: {{ 
                        $receipt->status == 'verified' ? '#d4edda' : 
                        ($receipt->status == 'completed' ? '#d1ecf1' : 
                        ($receipt->status == 'partial' ? '#fff3cd' : '#f8f9fa')) 
                    }};
                    color: {{ 
                        $receipt->status == 'verified' ? '#155724' : 
                        ($receipt->status == 'completed' ? '#0c5460' : 
                        ($receipt->status == 'partial' ? '#856404' : '#6c757d')) 
                    }};
                ">
                    {{ ucfirst($receipt->status ?? 'draft') }}
                </span>
            </p>
            <p><strong>Condition:</strong> {{ ucfirst($receipt->condition ?? 'N/A') }}</p>
            @if($receipt->delivery_note_number)
            <p><strong>Delivery Note:</strong> {{ $receipt->delivery_note_number }}</p>
            @endif
        </div>
    </div>
    
    @if($receipt->vehicle_number || $receipt->driver_name)
    <div class="document-info">
        <div class="info-box">
            <h4>Delivery Information:</h4>
            @if($receipt->vehicle_number)
            <p><strong>Vehicle:</strong> {{ $receipt->vehicle_number }}</p>
            @endif
            @if($receipt->driver_name)
            <p><strong>Driver:</strong> {{ $receipt->driver_name }}</p>
            @endif
            @if($receipt->driver_contact)
            <p><strong>Driver Contact:</strong> {{ $receipt->driver_contact }}</p>
            @endif
        </div>
        
        @if($receipt->storage_location || $receipt->bin_location)
        <div class="info-box">
            <h4>Storage Information:</h4>
            @if($receipt->storage_location)
            <p><strong>Location:</strong> {{ $receipt->storage_location }}</p>
            @endif
            @if($receipt->bin_location)
            <p><strong>Bin/Rack:</strong> {{ $receipt->bin_location }}</p>
            @endif
        </div>
        @endif
    </div>
    @endif
    
    <h3>Received Items</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Description</th>
                <th>Ordered Qty</th>
                <th>Received Qty</th>
                <th>Unit</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($receipt->items) && is_iterable($receipt->items) && count($receipt->items) > 0)
                @foreach($receipt->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->description ?? 'N/A' }}</td>
                    <td>{{ number_format($item->quantity_ordered ?? 0) }}</td>
                    <td>{{ number_format($item->quantity_received ?? 0) }}</td>
                    <td>{{ $item->unit ?? 'N/A' }}</td>
                    <td>Tsh {{ number_format($item->price ?? 0, 2) }}</td>
                    <td>Tsh {{ number_format(($item->quantity_received ?? 0) * ($item->price ?? 0), 2) }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">
                        No items received
                    </td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" style="text-align: right;">Total Items:</td>
                <td>{{ $receipt->total_items_received ?? 0 }}</td>
                <td style="text-align: right;">Total Quantity:</td>
                <td colspan="2">{{ number_format($receipt->total_quantity_received ?? 0) }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="6" style="text-align: right;">GRAND TOTAL:</td>
                <td>Tsh {{ number_format($receipt->total_amount ?? 0, 2) }}</td>
            </tr>
        </tfoot>
    </table>
    
    @if($receipt->quality_notes || $receipt->notes || $receipt->return_reason)
    <div style="margin-bottom: 20px;">
        <h4>Notes:</h4>
        @if($receipt->quality_notes)
        <p><strong>Quality Notes:</strong> {{ $receipt->quality_notes }}</p>
        @endif
        @if($receipt->notes)
        <p><strong>Additional Notes:</strong> {{ $receipt->notes }}</p>
        @endif
        @if($receipt->return_reason)
        <p><strong>Return Reason:</strong> {{ $receipt->return_reason }}</p>
        @endif
    </div>
    @endif
    
    <div class="signature-section">

    
    <div class="footer">
        <p>Verified On: {{ $receipt->verified_at ? $receipt->verified_at->format('F d, Y H:i') : 'Not verified' }}</p>
        <p>Generated on: {{ now()->format('F d, Y H:i:s') }}</p>
        <p>Document ID: {{ $receipt->receipt_number ?? 'N/A' }}</p>
    </div>
</body>
</html>