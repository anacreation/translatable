<?php
/**
 * Author: Xavier Au
 * Date: 19/6/2018
 * Time: 8:40 AM
 */

namespace Anacreation\Translatable\traits;


use Anacreation\Translatable\Models\Translatable;
use Illuminate\Database\Eloquent\Relations\Relation;

trait TranslatableTrait
{
    public function translations(): Relation {
        return $this->morphMany(Translatable::class, "model");
    }

    public static function createTranslations(array $content) {

        $newInstance = self::createNewInstance();

        foreach ($content as $code => $value) {

            $newInstance->createNewTranslation($code, $value);
        }

        return $newInstance;
    }

    public function updateTranslations(array $content) {

        foreach ($content as $code => $value) {

            if ($translation = $this->translations()->whereCode($code)
                                    ->first()) {

                $this->updateExistingTranslation($translation, $value);

            } else {

                $this->createNewTranslation($code, $value);

            }
        }

    }

    public function getTranslatablesAttribute(): array {

        $conversion = function (array $previous, Translatable $trans) {
            return $this->conversion($previous, $trans);
        };

        return $this->translations->reduce($conversion, []);
    }

    public function deleteTranslatableAttribute(
        string $key, string $locale = null
    ): void {

        $results = $this->translations()->locale($locale)->get();

        $results->each->deleteContentAttribute($key);

    }

    public function __get($key) {

        $result = parent::__get($key);

        return $result !== null ? $result : $this->parseTranslatableAttribute($key);
    }

    /**
     * @param $translation
     * @param $value
     */
    private function updateExistingTranslation($translation, $value): void {

        $oldData = $translation->content;

        $mergedData = $value + $oldData;

        $translation->content = $mergedData;

        $translation->save();
    }

    /**
     * @return mixed
     */
    private static function createNewInstance() {
        return static::create();
    }


    /**
     * @param $code
     * @param $value
     */
    private function createNewTranslation($code, $value): void {
        $this->translations()->create([
            "code"    => $code,
            'content' => $value
        ]);
    }

    private function conversion(array $previous, Translatable $trans): array {

        $previous[$trans->code] = $trans->content;

        return $previous;
    }

    /**
     * @param $key
     * @return null
     */
    private function parseTranslatableAttribute($key): ?string {

        if ($translation = $this->translations()->locale(app()->getLocale())
                                ->first()) {

            $decodedJson = $translation->content;

            return $decodedJson[$key] ?? null;
        }

        return null;
    }

}