<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSession extends Model
{
    protected $fillable = [
        'admission_year_id',
        'label',
        'exam_date',
        'created_by',
    ];

    protected $casts = [
        'exam_date' => 'date',
    ];

    public function year()
    {
        return $this->belongsTo(AdmissionYear::class, 'admission_year_id');
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
