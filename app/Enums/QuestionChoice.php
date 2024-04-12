<?php 

namespace App\Enums;

enum QuestionChoice: string {
    case C1 = 'Not at all';
    case C2 = 'Several days';
    case C3 = 'More than half the days';
    case C4 = 'Nearly every day';
}