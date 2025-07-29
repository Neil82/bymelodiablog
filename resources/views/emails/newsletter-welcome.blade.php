<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('ui.newsletter.welcome') }} - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f8fafc;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #9333ea 0%, #ec4899 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .content {
            padding: 30px 20px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #9333ea 0%, #ec4899 100%);
            color: white;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ __('ui.newsletter.welcome_title') }}</h1>
        </div>
        
        <div class="content">
            <p>{{ __('ui.newsletter.hello') }}{{ $subscriber->name ? ' ' . $subscriber->name : '' }},</p>
            
            <p>{{ __('ui.newsletter.welcome_message') }}</p>
            
            <p>{{ __('ui.newsletter.what_to_expect') }}</p>
            
            <ul>
                <li>{{ __('ui.newsletter.new_posts') }}</li>
                <li>{{ __('ui.newsletter.exclusive_content') }}</li>
                <li>{{ __('ui.newsletter.music_recommendations') }}</li>
            </ul>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('blog.index') }}" class="button">
                    {{ __('ui.newsletter.visit_blog') }}
                </a>
            </div>
            
            <p>{{ __('ui.newsletter.thanks') }}<br>
            <strong>{{ config('app.name') }}</strong></p>
        </div>
        
        <div class="footer">
            <p>{{ __('ui.newsletter.unsubscribe_info') }} 
                <a href="{{ $unsubscribeUrl }}">{{ __('ui.newsletter.unsubscribe') }}</a>
            </p>
            <p>© {{ date('Y') }} {{ config('app.name') }}. {{ __('ui.footer.all_rights_reserved') }}.</p>
        </div>
    </div>
</body>
</html>