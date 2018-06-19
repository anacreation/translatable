<?php
/**
 * Author: Xavier Au
 * Date: 19/6/2018
 * Time: 8:36 AM
 */

namespace Anacreation\Translatable\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Translatable extends Model
{
    protected $table = "translations";

    protected $fillable = ["content", "code"];

    public function model(): Relation {
        return $this->morphTo();
    }

}