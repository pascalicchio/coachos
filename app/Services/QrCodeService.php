<?php

namespace App\Services;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeService
{
    public function generateMemberQr($memberId)
    {
        $qrCode = new QrCode("gymmanageros://checkin/{$memberId}");
        $qrCode->setSize(300);
        $qrCode->setMargin(10);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        
        return base64_encode($result->getString());
    }

    public function generateMemberCard($member)
    {
        $qr = $this->generateMemberQr($member->id);
        
        return [
            'id' => $member->id,
            'name' => $member->name,
            'email' => $member->email,
            'phone' => $member->phone,
            'membership' => $member->membership?->name,
            'expiry_date' => $member->expiry_date,
            'qr_code' => $qr,
            'status' => $member->status,
        ];
    }
}
