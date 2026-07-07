<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Audit Logs Document | Fancy Decorators</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #333333;
            line-height: 1.4;
            padding: 20px;
        }
        .header {
            border-bottom: 2px solid #333333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 5px 0;
            font-size: 14px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .data-table th, .data-table td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        .data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 25px;
            margin-bottom: 10px;
            border-bottom: 1px solid #dddddd;
            padding-bottom: 5px;
        }
        .json-box {
            font-family: monospace;
            background-color: #f8f9fa;
            border: 1px solid #cccccc;
            padding: 10px;
            white-space: pre-wrap;
            font-size: 11px;
            margin-top: 5px;
        }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print();" style="padding: 8px 16px; font-weight: bold; cursor: pointer;">Print Document</button>
        <button onclick="window.close();" style="padding: 8px 16px; margin-left: 10px; cursor: pointer;">Close Window</button>
    </div>

    <div class="header">
        <h1>Fancy Decorators CMS</h1>
        <div style="font-size: 14px; color: #666666;">Security & Audit Log Document</div>
    </div>

    <table class="meta-table">
        <tr>
            <td><strong>Document Type:</strong> {{ isset($is_list) ? 'Activity Log Report Sheet' : 'Activity Audit Details Record' }}</td>
            <td style="text-align: right;"><strong>Generation Date:</strong> {{ date('Y-m-d H:i:s') }}</td>
        </tr>
    </table>

    @if(isset($is_list))
        <div class="section-title">Audit Log Entries</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Role</th>
                    <th>Module</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP Address</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->user ? $log->user->name : 'System' }}</td>
                        <td>{{ $log->role ? $log->role->label : 'N/A' }}</td>
                        <td>{{ $log->module }}</td>
                        <td>{{ strtoupper($log->action->value) }}</td>
                        <td>{{ $log->description }}</td>
                        <td>{{ $log->ip_address }}</td>
                        <td>{{ strtoupper($log->status->value) }}</td>
                        <td>{{ $log->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="section-title">Audit Details - Log #{{ $log->id }}</div>
        <table class="data-table">
            <tr>
                <th width="30%">UUID</th>
                <td>{{ $log->uuid }}</td>
            </tr>
            <tr>
                <th>Triggered By</th>
                <td>{{ $log->user ? $log->user->name . ' (' . $log->user->email . ')' : 'System Internal Action' }}</td>
            </tr>
            <tr>
                <th>Executor Role</th>
                <td>{{ $log->role ? $log->role->label : 'N/A' }}</td>
            </tr>
            <tr>
                <th>Target Module</th>
                <td>{{ $log->module }}</td>
            </tr>
            <tr>
                <th>Action Type</th>
                <td>{{ strtoupper($log->action->value) }}</td>
            </tr>
            <tr>
                <th>Description</th>
                <td>{{ $log->description }}</td>
            </tr>
            <tr>
                <th>Execution Status</th>
                <td>{{ strtoupper($log->status->value) }}</td>
            </tr>
            <tr>
                <th>IP Address</th>
                <td>{{ $log->ip_address }}</td>
            </tr>
            <tr>
                <th>Web Browser</th>
                <td>{{ $log->browser }}</td>
            </tr>
            <tr>
                <th>OS Engine</th>
                <td>{{ $log->operating_system }}</td>
            </tr>
            <tr>
                <th>Device Category</th>
                <td>{{ $log->device }}</td>
            </tr>
            <tr>
                <th>Target URL</th>
                <td>{{ $log->url }}</td>
            </tr>
            <tr>
                <th>HTTP Method</th>
                <td>{{ $log->method }}</td>
            </tr>
            <tr>
                <th>Session ID</th>
                <td>{{ $log->session_id }}</td>
            </tr>
            <tr>
                <th>Request Token ID</th>
                <td>{{ $log->request_id }}</td>
            </tr>
            <tr>
                <th>Timestamp</th>
                <td>{{ $log->created_at }}</td>
            </tr>
        </table>

        @if(!empty($log->old_values))
            <div class="section-title">Old Values (Prior State)</div>
            <div class="json-box">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</div>
        @endif

        @if(!empty($log->new_values))
            <div class="section-title">New Values (Modified State)</div>
            <div class="json-box">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</div>
        @endif
    @endif

    <div style="margin-top: 50px; font-size: 11px; text-align: center; border-top: 1px solid #dddddd; padding-top: 10px; color: #777777;">
        Confidential Document – Generated from Fancy Decorators Administration Control Panel.
    </div>

    <script>
        window.onload = function() {
            // Auto trigger print dialogue for user ease of access
            window.print();
        }
    </script>
</body>
</html>
