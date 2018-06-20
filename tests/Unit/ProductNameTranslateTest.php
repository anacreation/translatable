<?php

namespace Tests\Unit;

use App\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductNameTranslateTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_product() {
        $data = [
            'en'    => [
                'name' => 'Product in english'
            ],
            'zh_tw' => [
                'name' => 'Product in chinese'
            ]
        ];


        $newProduct = Product::createTranslations($data);

        $this->assertDatabaseHas("translations", [
            'model_id'   => $newProduct->id,
            'model_type' => get_class($newProduct),
            "code"       => "en",
        ]);

        $this->assertEquals($data['en']['name'], $newProduct->name);

        app()->setLocale('zh_tw');

        $this->assertEquals($data['zh_tw']['name'], $newProduct->name);

        app()->setLocale('bw');

        $this->assertEquals(null, $newProduct->name);
    }

    public function test_update_product() {
        $data = [
            'en'    => [
                'name' => 'Product in english',
                'note' => "note for en"
            ],
            'zh_tw' => [
                'name' => 'Product in chinese',
                'note' => "note for chinese"
            ]
        ];


        $newProduct = Product::createTranslations($data);

        $newData = [
            'zh_tw' => [
                'name' => "Product in chinese new"
            ]
        ];

        $newLanguageData = [
            'zh' => [
                'name' => "Product in simplified chinese",
                'note' => "note for simplified chinese"
            ]
        ];

        $newProduct->updateTranslations($newData);

        $newProduct->updateTranslations($newLanguageData);

        $this->assertDatabaseHas("translations", [
            'model_id'   => $newProduct->id,
            'model_type' => get_class($newProduct),
            "code"       => "zh_tw",
            "content"    => json_encode($newData["zh_tw"] + $data["zh_tw"])
        ]);

        $this->assertDatabaseHas("translations", [
            'model_id'   => $newProduct->id,
            'model_type' => get_class($newProduct),
            "code"       => "zh",
            "content"    => json_encode($newLanguageData["zh"])
        ]);

        $this->assertDatabaseHas("translations", [
            'model_id'   => $newProduct->id,
            'model_type' => get_class($newProduct),
            "code"       => "en",
            "content"    => json_encode($data["en"])
        ]);
    }
}
