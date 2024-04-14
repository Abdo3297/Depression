<?php 

namespace App\Enums;
enum Status: string {
    case C1 = 'pending';
    case C2 = 'accepted';
    case C3 = 'rejected';
}