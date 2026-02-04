<!DOCTYPE html>
<html>
<head>
    <title>{{ $isReset ? 'Password Reset' : 'Account Created' }}</title>
</head>
<body>
    <h2>{{ $isReset ? 'Password Reset Notification' : 'Welcome to Our Platform!' }}</h2>
    
    <p>Dear {{ $company->name }},</p>
    
    @if($isReset)
    <p>Your company account password has been successfully reset.</p>
    @else
    <p>Your company account has been successfully created.</p>
    @endif
    
    <p>Here are your login credentials:</p>
    
    <ul>
        <li><strong>Email:</strong> {{ $company->email }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
    </ul>
    
    @if(!$isReset)
    <p>Please log in and change your password after first login for security.</p>
    @endif
    
    <p><strong>Security Notice:</strong> Keep these credentials confidential and do not share them with anyone.</p>
    
    <p>Best regards,<br>
    Your Platform Team</p>
</body>
</html>