<!DOCTYPE html>
<html>
<head>
    <title>Quotation {{ $quotation->quotation_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .company-info { margin-bottom: 30px; }
        .client-info { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total-row { font-weight: bold; }
        .footer { margin-top: 50px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>QUOTATION</h1>
        <h2>{{ $quotation->quotation_number }}</h2>
    </div>
    
    <div class="company-info">
        <h3>{{ $quotation->company->name ?? Auth::user()->name }}</h3>
        <p>{{ $quotation->company->address ?? 'Head quater' }}</p>
        <p>Phone: {{ $quotation->company->phone ?? 'N/A' }} | Email: {{ $quotation->company->email ?? Auth::user()->email }}</p>
    </div>
    
    <div class="client-info">
        <h3>Bill To:</h3>
        <p><strong>{{ $quotation->client_name }}</strong></p>
        @if($quotation->client_email)<p>Email: {{ $quotation->client_email }}</p>@endif
        @if($quotation->client_phone)<p>Phone: {{ $quotation->client_phone }}</p>@endif
    </div>
    
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
            @foreach($quotation->formatted_items as $item)
            <tr>
                <td>{{ $item['description'] ?? '' }}</td>
                <td>{{ $item['quantity'] ?? 0 }}</td>
                <td>Tsh {{ number_format($item['price'] ?? 0, 2) }}</td>
                <td>Tsh {{ number_format(($item['quantity'] ?? 0) * ($item['price'] ?? 0), 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" style="text-align: right;">Total:</td>
                <td>Tsh {{ number_format($quotation->total, 2) }}</td>
            </tr>
        </tbody>
    </table>
    
    @if($quotation->notes)
    <div class="notes">
        <h4>Notes:</h4>
        <p>{{ $quotation->notes }}</p>
    </div>
    @endif
    
    <div class="footer" style="margin-left: 40%;">
        <p>Quotation Date: {{ $quotation->date->format('F d, Y') }}</p>
        <p>prepared by <strong>{{ $quotation->company->name ?? Auth::user()->name }}</strong> Company Limited</p>
        <p>Thank you for your business!</p>
    </div>
</body>
</html>