<?php

namespace Tests\Unit;

use App\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GetProductTranslatableTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_get_all_translatables() {

        $data = [
            'en'    => [
                'name' => 'Product in english'
            ],
            'zh_tw' => [
                'name' => 'Product in chinese'
            ]
        ];

        $newProduct = Product::createTranslations($data);

        $retrievedData = $newProduct->translatables;

        $this->assertEquals($data, $retrievedData);

    }
}
