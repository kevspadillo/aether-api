<?php

namespace App\Helpers;

class MemberHelper
{
    public static function generateReferenceCode($transaction)
    {
        $currentDate   = date('ymdHis');
        $hash          = sha1($transaction . $currentDate);
        $referenceCode = substr($hash, 0, 10);
        return strtoupper($referenceCode);
    }
}
