<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class ServicesServiceProviders extends Model
{

    use BelongsToTenant;
    protected $table = 'services_services_providers';

    public function service_provider()
    {
        return $this->hasMany(ServiceProvider::class, 'id', 'service_providers_id');
    }
    public function service_provider_contact()
    {
        return $this->hasMany(ServiceProviderContact::class, 'service_providers_id', 'service_providers_id');
    }

}
