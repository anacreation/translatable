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

    public function __get($key) {
        $result = parent::__get($key);

        if ($result !== null) {
            return $result;
        }

        if ($translation = $this->translations()->whereCode(app()->getLocale())
                                ->first()) {

            $decodedJson = json_decode($translation->content, true);

            return $decodedJson[$key] ?? null;

        }

        return null;
    }

    /**
     * @param $translation
     * @param $value
     */
    private function updateExistingTranslation($translation, $value): void {

        $oldData = json_decode($translation->content, true);

        $mergedData = $value + $oldData;

        $translation->content = json_encode($mergedData);

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
            'content' => json_encode($value)
        ]);
    }
}