<?php

namespace Tests\Feature\Api\V1;

use App\Models\Category;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    /**
     * Test API V1 get list categories success
     *
     * @return void
     */
    public function test_get_list_categories_success()
    {
        $response = $this->json('GET', '/api/v1/categories');

        $response->assertOk()
                 ->assertSeeText('data')
                 ->assertSeeText('pagination');
    }

    /**
     * Test API V1 get detail categories success
     *
     * @return void
     */
    public function test_get_detail_categories_success()
    {
        $category = Category::factory()->create();

        $response = $this->json('GET', '/api/v1/categories/'.$category->id);

        $response->assertOk()
                 ->assertJson([
                     'status' => true,
                 ]);
    }

    /**
     * Test API V1 get detail categories failed
     *
     * @return void
     */
    public function test_get_detail_categories_failed()
    {
        $category = Category::factory()->create();

        $response = $this->json('GET', '/api/v1/categories/xx'.$category->id);
        $response->assertNotFound();
    }

    /**
     * Test API V1 create categories success
     *
     * @return void
     */
    public function test_create_categories_success()
    {
        $data = [
            'name' => 'name',
            'enable' => 1,
        ];

        $response = $this->json('POST', '/api/v1/categories', $data);
        $response->assertCreated();

        $response = json_decode($response->getContent());
        $dataResponse = $response->data;
        $categoryId = $dataResponse->id;
        $category = Category::find($categoryId);

        $this->assertSame($data['name'], $category->name);
        $this->assertSame($data['enable'], $category->enable);
    }

    /**
     * Test API V1 create categories success
     *
     * @return void
     */
    public function test_create_categories_failed()
    {
        $data = [
            'name' => 'name',
        ];

        $response = $this->json('POST', '/api/v1/categories', $data);
        $response->assertUnprocessable();
    }

    /**
     * Test API V1 update categories success
     *
     * @return void
     */
    public function test_update_categories_success()
    {
        $category = Category::factory()->create();
        $data = [
            'name' => 'name update',
            'enable' => 0,
        ];

        $response = $this->json('PUT', '/api/v1/categories/'.$category->id, $data);
        $response->assertOk();

        $response = json_decode($response->getContent());
        $dataResponse = $response->data;
        $categoryId = $dataResponse->id;
        $category = Category::find($categoryId);

        $this->assertSame($data['name'], $category->name);
        $this->assertSame($data['enable'], $category->enable);
    }

    /**
     * Test API V1 update categories success
     *
     * @return void
     */
    public function test_update_categories_failed()
    {
        $category = Category::factory()->create();
        $data = [
            'name' => 'name update',
        ];

        $response = $this->json('PUT', '/api/v1/categories/'.$category->id, $data);
        $response->assertUnprocessable();
    }

    /**
     * Test API V1 delete category success
     *
     * @return void
     */
    public function test_delete_categories_success()
    {
        $category = Category::factory()->create();

        $response = $this->json('DELETE', '/api/v1/categories/'.$category->id);

        $response->assertOk()
                 ->assertJson([
                     'status' => true,
                 ]);
    }

    /**
     * Test API V1 delete category failed
     *
     * @return void
     */
    public function test_delete_categories_failed()
    {
        $category = Category::factory()->create();

        $response = $this->json('DELETE', '/api/v1/categories/xx'.$category->id);
        $response->assertBadRequest();
    }
}
