<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        
        .pass-container {
            max-width: 750px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        .pass-header {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 30px;
        }
        
        .pass-header h1 {
            font-size: 32px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .pass-header p {
            font-size: 14px;
            color: #dbeafe;
        }
        
        .section {
            padding: 25px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .section-header {
            color: #1f2937;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            color: #6b7280;
        }
        
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        .grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
        }
        
        .info-block {
            margin-bottom: 15px;
        }
        
        .info-block:last-child {
            margin-bottom: 0;
        }
        
        .label {
            font-size: 11px;
            color: #6b7280;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
            letter-spacing: 0.5px;
        }
        
        .value {
            font-size: 16px;
            color: #1f2937;
            font-weight: 600;
        }
        
        .status-badge {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 5px;
        }
        
        .pass-id-section {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .pass-id-block {
            text-align: center;
        }
        
        .pass-id-block .value {
            font-size: 24px;
            color: #2563eb;
        }
        
        .qr-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 25px;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .qr-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }
        
        .qr-code {
            width: 200px;
            height: 200px;
            background: white;
            padding: 10px;
            border: 2px dashed #d1d5db;
            border-radius: 5px;
        }
        
        .qr-note {
            font-size: 10px;
            color: #6b7280;
            margin-top: 10px;
            text-align: center;
            max-width: 300px;
            line-height: 1.4;
        }
        
        .approval-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            padding: 25px;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
        }
        
        .approval-card {
            border-left: 4px solid #3b82f6;
            padding: 12px;
            background: white;
            border-radius: 3px;
        }
        
        .approval-card.admin {
            border-left-color: #a78bfa;
        }
        
        .approval-title {
            font-size: 11px;
            color: #6b7280;
            font-weight: bold;
            margin-bottom: 6px;
        }
        
        .approval-status {
            display: flex;
            align-items: center;
            font-size: 14px;
            font-weight: bold;
            color: #059669;
        }
        
        .approval-status::before {
            content: '✓';
            margin-right: 6px;
            font-size: 16px;
        }
        
        .footer {
            padding: 15px 25px;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
            background: white;
            border-top: 1px solid #e5e7eb;
        }
        
        .divider {
            border-bottom: 2px solid #e5e7eb;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="pass-container">
        <!-- Header -->
        <div class="pass-header">
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                <img src="{{ public_path('images/logo.jpg') }}" alt="Manmohan Memorial Polytechnic" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255,255,255,0.5);">
                <div>
                    <h1>Hostel Visitor Pass</h1>
                    <p>Manmohan Memorial Polytechnic — Valid for approved visit</p>
                </div>
            </div>
        </div>

        <!-- Pass ID and Status -->
        <div class="section pass-id-section">
            <div class="pass-id-block">
                <div class="label">Pass ID</div>
                <div class="value">PASS-{{ str_pad($visit->id, 5, '0', STR_PAD_LEFT) }}</div>
            </div>
            <div class="pass-id-block">
                <div class="label">Status</div>
                <div class="status-badge">APPROVED</div>
            </div>
            <div class="pass-id-block">
                <div class="label">Valid Date</div>
                <div class="value" style="font-size: 16px;">{{ \Carbon\Carbon::parse($visit->visit_date)->format('M d, Y') }}</div>
            </div>
        </div>

        <!-- Visitor Information -->
        <div class="section">
            <div class="divider" style="margin-top: 0;"></div>
            <div class="grid-2">
                <div class="info-block">
                    <div class="label">Visitor Name</div>
                    <div class="value">{{ $visit->visitor_name }}</div>
                </div>
                <div class="info-block">
                    <div class="label">Phone Number</div>
                    <div class="value">{{ $visit->phone ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <!-- Student Information -->
        <div class="section">
            <div class="grid-2">
                <div class="info-block">
                    <div class="label">Student Name</div>
                    <div class="value">{{ $visit->student->name }}</div>
                </div>
                <div class="info-block">
                    <div class="label">Room Number</div>
                    <div class="value">{{ $visit->student->bed ? $visit->student->bed->room->room_number : 'N/A' }}</div>
                </div>
                <div class="info-block">
                    <div class="label">Student Email</div>
                    <div class="value">{{ $visit->student->email }}</div>
                </div>
                <div class="info-block">
                    <div class="label">Approved Date</div>
                    <div class="value">{{ $visit->created_at->format('M d, Y H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- Visit Purpose -->
        <div class="section">
            <div class="info-block">
                <div class="label">Visit Purpose</div>
                <div class="value">{{ $visit->purpose ?? 'Not specified' }}</div>
            </div>
        </div>

        <!-- QR Code Section -->
        <div class="qr-section">
            <div class="qr-label">Scan for Verification</div>
            <div class="qr-code">
                @php
                    $qrText = json_encode([
                        'pass_id' => 'PASS-' . str_pad($visit->id, 5, '0', STR_PAD_LEFT),
                        'visitor_name' => $visit->visitor_name,
                        'visitor_phone' => $visit->phone,
                        'student_name' => $visit->student->name,
                        'room_number' => $visit->student->bed ? $visit->student->bed->room->room_number : 'N/A',
                        'visit_date' => $visit->visit_date,
                        'purpose' => $visit->purpose,
                        'approved_at' => $visit->created_at,
                        'status' => $visit->status
                    ]);
                    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($qrText);
                @endphp
                <img src="{{ $qrUrl }}" alt="QR Code" style="width: 100%; height: auto;">
            </div>
            <div class="qr-note">
                Show this QR code at the hostel gate or entrance for quick verification
            </div>
        </div>

        <!-- Approval Details -->
        <div class="approval-section">
            <div class="approval-card">
                <div class="approval-title">Student Review</div>
                <div class="approval-status">Accepted</div>
            </div>
            <div class="approval-card admin">
                <div class="approval-title">Admin Approval</div>
                <div class="approval-status">Approved</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Please present this pass along with a valid ID for verification</p>
            <p style="margin-top: 5px;">Generated on {{ now()->format('M d, Y H:i A') }}</p>
            <p style="margin-top: 5px;">This pass is valid only for the date mentioned above</p>
        </div>
    </div>
</body>
</html>
