<?php

namespace Tests\Unit;

use App\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class DeleteLocale extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_delete_entire_local_translation() {

        $data = [
            'en'    => [
                'name'  => 'Product in english',
                'note'  => 'Product note in english',
                'attr1' => 'Product attr1 in english'
            ],
            'zh_tw' => [
                'name'  => 'Product in chinese',
                'note'  => 'Product note in chinese',
                'attr1' => 'Product attr1 in chinese'
            ]
        ];

        $newProduct = Product::createTranslations($data);

        $newProduct->deleteTranslatableWithLocale("zh_tw");

        $this->assertDatabaseMissing('translations', [
            'model_id'   => $newProduct->id,
            'model_type' => get_class($newProduct),
            'code'       => "zh_tw"
        ]);
    }

}
