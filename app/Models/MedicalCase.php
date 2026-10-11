<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalCase extends Model
{
    // Database table ka actual naam
    protected $table = 'cases';

    protected $fillable = [
        'case_number',
        'patient_reference',
        'surgeon_name',
        'implant_type',
        'status',
        'priority',
        'surgery_date',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'surgery_date' => 'date',
        ];
    }

    // User who created this medical case
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}