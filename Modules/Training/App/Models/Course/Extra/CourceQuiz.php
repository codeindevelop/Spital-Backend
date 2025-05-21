<?php

namespace Modules\Training\App\Models\Course\Extra;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourceQuiz extends Model
{
    use HasFactory;


    protected $fillable = [
        'course_id',
        'quizze_title',
        'quizze_question',
        'quizze_res_1',
        'quizze_res_2',
        'quizze_res_3',
        'quizze_res_4',
        'quizze_right_answer',

        'active',

    ];
}
