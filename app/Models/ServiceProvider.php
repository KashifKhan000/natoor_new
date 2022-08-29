<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class ServiceProvider extends Model
{
    use BelongsToTenant;

    
    protected $table = 'service_providers';

    public function service_provider_contact()
    {
        return $this->hasMany(ServiceProviderContact::class, 'service_providers_id', 'id');
    }
}
