<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Validator\Constraints as Assert;

class Country extends Model
{
    protected $fillable = ['name', 'canonicalName'];

    public function cities()
    {
        return $this->hasMany(City::class, 'country_id', 'id');
    }

    public static function getByCanonName(string $canonicalName)
    {
        return parent::select(['name', 'id'])
            ->where('canonicalName', $canonicalName)
            ->firstOrFail();
    }
}
