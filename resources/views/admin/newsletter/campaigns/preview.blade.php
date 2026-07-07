<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campaign Preview: {{ $campaign->title }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f5f5f7;
            padding: 20px;
        }
        .preview-header {
            background-color: #ffffff;
            border-bottom: 1px solid #e0e0e0;
            padding: 15px 20px;
            border-radius: 8px 8px 0 0;
        }
        .preview-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            max-width: 800px;
            margin: 0 auto;
        }
        .preview-body {
            padding: 30px;
            min-height: 400px;
        }
    </style>
</head>
<body>
    <div class="preview-container">
        <div class="preview-header">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Campaign Email Preview</h5>
                <button onclick="window.close()" class="btn btn-sm btn-outline-secondary">Close Preview</button>
            </div>
            <div class="row g-2 small text-muted">
                <div class="col-2 text-end fw-bold">From:</div>
                <div class="col-10">{{ $campaign->sender_name }} &lt;{{ $campaign->sender_email }}&gt;</div>
                
                <div class="col-2 text-end fw-bold">Subject:</div>
                <div class="col-10 text-dark fw-semibold">{{ $campaign->subject }}</div>
                
                @if($campaign->preview_text)
                    <div class="col-2 text-end fw-bold">Snippet:</div>
                    <div class="col-10"><em>{{ $campaign->preview_text }}</em></div>
                @endif
            </div>
        </div>
        <div class="preview-body">
            {!! $htmlContent !!}
        </div>
    </div>
</body>
</html>
