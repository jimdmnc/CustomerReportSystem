<!DOCTYPE html>
<html lang="en">
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 14px; color: #1f2937; line-height: 1.5;">
    <p>{!! nl2br(e($messageText)) !!}</p>

    <p style="color: #6b7280; font-size: 12px;">
        Attachment: {{ $pdfName }}<br>
        Customer: {{ $customerName }}
    </p>
</body>
</html>