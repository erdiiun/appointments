<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyWorkDay extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * @var string
     */
    protected string $table = 'company_work_days';
}
