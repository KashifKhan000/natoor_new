<?PHP

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Multitenantable
{

    protected static function bootMultitenantable()
    {
        if (auth()->check() && auth()->user()->type > 0) {
            static::creating(function ($model) {
                $model->dns = request()->getHost();
            });

            static::addGlobalScope('dns', function (Builder $builder) {
                $builder->where('dns', request()->getHost());
            });
        }
    }
}
