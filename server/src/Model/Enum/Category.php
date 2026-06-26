<?php
declare(strict_types=1);

namespace App\Model\Enum;

enum Category: int
{
    const int JUNIOR = 0<<1;
    const int SENIOR = 0<<2;
    const int SUPER_SENIOR  = 0<<3;
    const int LADY = 0<<4;
    const int LAW_ENFORCEMENT = 0<<5;
    const int MILITARY = 0<<6;
    const int FOREIGN =  0<<7;
    const int DISTINGUISHED_PARTICIPANT = 0<<8;
    const int YOUTH = 0<<9;
}