<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThesisAdviseeMember extends Model
{
    protected $fillable = [
        'thesis_advisee_id',
        'student_name',
    ];

    public function advisee()
    {
        return $this->belongsTo(ThesisAdvisee::class, 'thesis_advisee_id');
    }
}
