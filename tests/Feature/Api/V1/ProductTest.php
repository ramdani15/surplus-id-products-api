<?php

namespace Tests\Feature\Api\V1;

use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * Test API V1 get list products success
     *
     * @return void
     */
    public function test_get_list_products_success()
    {
        $response = $this->json('GET', '/api/v1/products');

        $response->assertOk()
                 ->assertSeeText('data')
                 ->assertSeeText('pagination');
    }

    /**
     * Test API V1 get detail products success
     *
     * @return void
     */
    public function test_get_detail_products_success()
    {
        $product = Product::factory()->create();

        $response = $this->json('GET', '/api/v1/products/'.$product->id);

        $response->assertOk()
                 ->assertJson([
                     'status' => true,
                 ]);
    }

    /**
     * Test API V1 get detail products failed
     *
     * @return void
     */
    public function test_get_detail_products_failed()
    {
        $product = Product::factory()->create();

        $response = $this->json('GET', '/api/v1/products/xx'.$product->id);
        $response->assertNotFound();
    }

    /**
     * Test API V1 create products success
     *
     * @return void
     */
    public function test_create_products_success()
    {
        Storage::fake('avatars');

        $category = Category::factory()->create();
        $data = [
            'name' => 'name',
            'description' => 'description',
            'enable' => 1,
            'image' => UploadedFile::fake()->image('avatar.jpg'),
            'category_id' => $category->id,
        ];

        $response = $this->post('/api/v1/products', $data);
        $response->assertCreated();

        $response = json_decode($response->getContent());
        $dataResponse = $response->data;
        $productId = $dataResponse->id;
        $product = Product::find($productId);

        $this->assertSame($data['name'], $product->name);
        $this->assertSame($data['description'], $product->description);
        $this->assertSame($data['enable'], $product->enable);

        $totalImages = Image::whereHas('productImages', function ($q) use ($product) {
            return $q->whereProductId($product->id);
        })->count();
        $totalCategories = Category::whereHas('categoryProducts', function ($q) use ($product) {
            return $q->whereProductId($product->id);
        })->count();

        $this->assertSame(count($product->images), $totalImages);
        $this->assertSame(count($product->categories), $totalCategories);
    }

    /**
     * Test API V1 create products failed
     *
     * @return void
     */
    public function test_create_products_failed()
    {
        $data = [
            'name' => 'name',
        ];

        $response = $this->json('POST', '/api/v1/products', $data);
        $response->assertUnprocessable();
    }

    /**
     * Test API V1 update products success
     *
     * @return void
     */
    public function test_update_products_success()
    {
        $product = Product::factory()->create();
        Storage::fake('avatars');

        $category = Category::factory()->create();
        $data = [
            'name' => 'name update',
            'description' => 'description update',
            'enable' => 1,
            'category_id' => $category->id,
        ];

        $response = $this->post('/api/v1/products/'.$product->id, $data);
        $response->assertOk();

        $response = json_decode($response->getContent());
        $dataResponse = $response->data;
        $productId = $dataResponse->id;
        $product = Product::find($productId);

        $this->assertSame($data['name'], $product->name);
        $this->assertSame($data['description'], $product->description);
        $this->assertSame($data['enable'], $product->enable);

        $totalImages = Image::whereHas('productImages', function ($q) use ($product) {
            return $q->whereProductId($product->id);
        })->count();
        $totalCategories = Category::whereHas('categoryProducts', function ($q) use ($product) {
            return $q->whereProductId($product->id);
        })->count();

        $this->assertSame(count($product->images), $totalImages);
        $this->assertSame(count($product->categories), $totalCategories);
    }

    /**
     * Test API V1 update products success
     *
     * @return void
     */
    public function test_update_products_failed()
    {
        $product = Product::factory()->create();
        $data = [
            'name' => 'name update',
        ];

        $response = $this->json('POST', '/api/v1/products/'.$product->id, $data);
        $response->assertUnprocessable();
    }

    /**
     * Test API V1 delete product success
     *
     * @return void
     */
    public function test_delete_products_success()
    {
        $product = Product::factory()->create();

        $response = $this->json('DELETE', '/api/v1/products/'.$product->id);

        $response->assertOk()
                 ->assertJson([
                     'status' => true,
                 ]);
    }

    /**
     * Test API V1 delete product failed
     *
     * @return void
     */
    public function test_delete_products_failed()
    {
        $product = Product::factory()->create();

        $response = $this->json('DELETE', '/api/v1/products/xx'.$product->id);
        $response->assertBadRequest();
    }
}
