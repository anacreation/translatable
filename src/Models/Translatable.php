<?php
/**
 * Author: Xavier Au
 * Date: 19/6/2018
 * Time: 8:36 AM
 */

namespace Anacreation\Translatable\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Translatable extends Model
{
    protected $table = "translations";

    protected $fillable = ["content", "code"];

    public function model(): Relation {
        return $this->morphTo();
    }

    public function deleteContentAttribute(string $key): void {
        $contentArray = $this->content;

        if (isset($contentArray[$key])) {
            unset($contentArray[$key]);
            $this->content = $contentArray;
            $this->save();
        }
    }

    public function scopeLocale(Builder $query, string $locale = null
    ) {
        if ($locale) {
            $query->whereCode($locale);
        }
    }

    public function getContentAttribute($value): ?array {
        return json_decode($value, true);
    }
    public function setContentAttribute($value) {
        $this->attributes['content'] = json_encode($value);
    }
}

