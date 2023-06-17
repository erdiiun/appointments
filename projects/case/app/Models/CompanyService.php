<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyService extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * @var string
     */
    protected string $table = 'company_services';
}
