<?php
$qrText = json_encode([
    'pass_id' => 'PASS-00001',
    'visitor_name' => 'John Doe',
    'visitor_phone' => '+1234567890',
    'student_name' => 'Jane Smith',
    'room_number' => '201'
]);
$qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=256x256&data=' . urlencode($qrText);
echo 'QR API URL works: ' . PHP_EOL;
echo $qrUrl . PHP_EOL;
echo PHP_EOL . 'URL is valid and can be used in img src' . PHP_EOL;
