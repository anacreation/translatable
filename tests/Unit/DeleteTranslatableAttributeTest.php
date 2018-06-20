<?php

namespace Tests\Unit;

use App\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class DeleteTranslatableAttributeTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_delete_translatable_attribute() {

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

        $newProduct = Product::createModelWithTranslations([], $data);

        $newProduct->deleteTranslatableAttribute('attr1');

        app()->setLocale("en");

        $this->assertEquals($newProduct->attr1, null);

        app()->setLocale("zh_tw");
        $this->assertEquals($newProduct->attr1, null);
    }

    public function test_delete_translatable_attribute_with_single_locale() {

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

        $newProduct = Product::createModelWithTranslations([], $data);

        $newProduct->deleteTranslatableAttribute('attr1', 'en');

        app()->setLocale("en");

        $this->assertEquals($newProduct->attr1, null);

        app()->setLocale("zh_tw");

        $this->assertEquals($newProduct->attr1, $data["zh_tw"]["attr1"]);
    }

}
