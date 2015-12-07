<?php namespace Arcanedev\Settings\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class     Setting
 *
 * @package  Arcanedev\Settings\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  int     id
 * @property  string  key
 * @property  string  value
 *
 * @method    \Illuminate\Database\Eloquent\Collection  get(array $columns = ['*'])
 * @method    \Illuminate\Database\Eloquent\Builder     insert(array $values)
 * @method    \Illuminate\Support\Collection            lists(string $column, mixed $key = null)
 * @method    \Illuminate\Database\Eloquent\Builder     where(mixed $column, string $operator = null, mixed $value = null, string $boolean = 'and')
 * @method    \Illuminate\Database\Eloquent\Builder     whereIn(string $key, mixed $value)
 */
class Setting extends Model
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
