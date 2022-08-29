<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class UserLog extends Model
{
    use BelongsToTenant;

    protected $table = 'user_log';
    protected $perPage = 15;
    protected $locale = 'en';
}
