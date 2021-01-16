<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use WouterJ\EloquentBundle\Facade\Db as DB;
use Symfony\Component\Validator\Constraints as Assert;

class City extends Model
{
    protected $fillable = ['name', 'country_id'];

    public $casts = [
        'name' => 'string',
        'country_id' => 'integer',
    ];

    /**
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Name cannot be longer than {{ limit }} characters",
     * )
     * @Assert\NotBlank
     * @Assert\Regex(
     *     pattern="/^[A-Z][a-z]+$/",
     *     message="Name must start with capital leter and include only letters",
     * )
     */
    public $name;

    /**
     * @Assert\NotBlank
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}.",
     * )
     */
    public $country_id;

    public function country()
    {
        return $this->belongsTo('App\Model\Country');
    }

    public function save(array $options = [])
    {
        DB::raw('lock tables country write');

        $data = parent::save($options);

        DB::raw('unlock tables');

        return $data;
    }

    /**
     * @Assert\IsTrue(message="The country does not exists.")
     */
    public function getCountry()
    {
        return $this->country !== null;
    }

    /**
     * @Assert\IsTrue(message="The city already exists.")
     */
    public function getCity()
    {
        return !static::where([
            'name' => $this->name
        ])->exists();
    }
}
