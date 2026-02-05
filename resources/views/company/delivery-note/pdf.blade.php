<!DOCTYPE html>
<html>
<head>
    <title>Delivery Note {{ $deliveryNote->delivery_note_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .company-info { margin-bottom: 30px; }
        .client-info { margin-bottom: 30px; }
        .details { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total-row { font-weight: bold; background-color: #f8f9fa; }
        .footer { margin-top: 50px; font-size: 12px; }
        .signature { margin-top: 50px; border-top: 1px solid #333; padding-top: 10px; }
        .section-title { background-color: #e9ecef; padding: 5px 10px; margin: 20px 0 10px 0; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>DELIVERY NOTE</h1>
        <h2>{{ $deliveryNote->delivery_note_number }}</h2>
    </div>
    
    <div class="company-info">
        <h3>{{ Auth::user()->name ?? 'Your Company' }}</h3>
        <p>{{ $deliveryNote->company->address ?? 'Address' }}</p>
        <p>Phone: {{ $deliveryNote->company->phone ?? 'N/A' }} | Email: {{ $deliveryNote->company->email ?? 'N/A' }}</p>
    </div>
    
    <div class="details">
        <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
            <div style="width: 48%;">
                <h4>Quotation Details</h4>
                <p><strong>Quotation #:</strong> {{ $deliveryNote->quotation->quotation_number ?? 'N/A' }}</p>
                <p><strong>Client:</strong> {{ $deliveryNote->quotation->client_name ?? 'N/A' }}</p>
                <p><strong>Client Email:</strong> {{ $deliveryNote->quotation->client_email ?? 'N/A' }}</p>
                <p><strong>Client Phone:</strong> {{ $deliveryNote->quotation->client_phone ?? 'N/A' }}</p>
            </div>
            <div style="width: 48%;">
                <h4>Delivery Details</h4>
                <p><strong>Delivery Date:</strong> {{ $deliveryNote->delivery_date->format('F d, Y') }}</p>
                @if($deliveryNote->dispatch_date)
                <p><strong>Dispatch Date:</strong> {{ $deliveryNote->dispatch_date->format('F d, Y') }}</p>
                @endif
                <p><strong>Status:</strong> {{ ucfirst($deliveryNote->status) }}</p>
                @if($deliveryNote->vehicle_number)
                <p><strong>Vehicle #:</strong> {{ $deliveryNote->vehicle_number }}</p>
                @endif
            </div>
        </div>
        
        @if($deliveryNote->delivery_address)
        <div class="section-title">Delivery Address</div>
        <div style="white-space: pre-line; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            {{ $deliveryNote->delivery_address }}
        </div>
        @endif
        
        @if($deliveryNote->delivery_contact_person || $deliveryNote->delivery_contact_phone)
        <div class="section-title">Contact Information</div>
        <p>
            @if($deliveryNote->delivery_contact_person)
            <strong>Contact Person:</strong> {{ $deliveryNote->delivery_contact_person }}
            @endif
            @if($deliveryNote->delivery_contact_phone)
            <strong>Phone:</strong> {{ $deliveryNote->delivery_contact_phone }}
            @endif
        </p>
        @endif
        
        @if($deliveryNote->driver_name || $deliveryNote->driver_contact)
        <div class="section-title">Driver Information</div>
        <p>
            @if($deliveryNote->driver_name)
            <strong>Driver Name:</strong> {{ $deliveryNote->driver_name }}
            @endif
            @if($deliveryNote->driver_contact)
            <strong>Driver Contact:</strong> {{ $deliveryNote->driver_contact }}
            @endif
        </p>
        @endif
    </div>
    
    <div class="section-title">Delivery Items</div>
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deliveryNote->formatted_items as $item)
            <tr>
                <td>{{ $item['description'] ?? '' }}</td>
                <td>{{ $item['quantity'] ?? 0 }}</td>
                <td>Tsh {{ number_format($item['price'] ?? 0, 2) }}</td>
                <td>Tsh {{ number_format(($item['quantity'] ?? 0) * ($item['price'] ?? 0), 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" style="text-align: right;">Total:</td>
                <td>Tsh {{ number_format($deliveryNote->total, 2) }}</td>
            </tr>
        </tbody>
    </table>
    
    @if($deliveryNote->delivery_notes)
    <div class="section-title">Delivery Notes</div>
    <div style="white-space: pre-line; padding: 10px; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 20px;">
        {{ $deliveryNote->delivery_notes }}
    </div>
    @endif
    
    
    </div>
    
    <div class="footer">
        <p>Generated on: {{ now()->format('F d, Y H:i:s') }}</p>
                <p>Prepared by {{ $deliveryNote->company->name ?? Auth::user()->name }} under provider {{ $deliveryNote->company->provider ?? Auth::user()->name}}</p>
        <p>Thank you for your business!</p>
    </div>
</body>
</html>