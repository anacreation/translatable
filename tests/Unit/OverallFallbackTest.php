<?php

namespace Tests\Unit;

use App\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class OverallFallbackTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_fetch_product_attribute_with_no_country_code() {
        $data = [
            'en'    => [
                'name' => 'Product in english',
            ],
            'zh_tw' => [
                'name' => 'Product in chinese'
            ]
        ];


        $newProduct = Product::createModelWithTranslations([], $data);

        $newProduct->fallback = true;

        app()->setLocale("es");

        $this->assertEquals($data['en']['name'], $newProduct->name);

    }

    public function test_missing_specific_product_attribute_in_country_code() {
        $data = [
            'en'    => [
                'name' => 'Product in english',
                'note' => 'note in english'
            ],
            'zh_tw' => [
                'name' => 'Product in chinese'
            ]
        ];


        $newProduct = Product::createModelWithTranslations([], $data);

        $newProduct->fallback = true;

        app()->setLocale("zh_tw");

        $this->assertEquals($data['zh_tw']['name'], $newProduct->name);

        $this->assertEquals($data['en']['note'], $newProduct->note);
    }

    public function test_no_fallback() {
        $data = [
            'en'    => [
                'name' => 'Product in english',
                'note' => 'note in english'
            ],
            'zh_tw' => [
                'name' => 'Product in chinese'
            ]
        ];


        $newProduct = Product::createModelWithTranslations([], $data);

        app()->setLocale("es");

        $this->assertEquals(null, $newProduct->name);

        app()->setLocale("zh_tw");

        $this->assertEquals(null, $newProduct->note);

        $this->assertEquals($data["zh_tw"]["name"], $newProduct->name);
    }
}
