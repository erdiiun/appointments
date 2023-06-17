<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * @var string
     */
    protected string $table = 'services';

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_services');
    }
}
