<?php

namespace R64\Webforms\Tests\Feature\Http\Controllers;

use R64\Webforms\Models\Form;
use R64\Webforms\Tests\Feature\Models\User;
use R64\Webforms\Tests\TestCase;

class AdminFormStoreControllerTest extends TestCase
{
    /**
     * @test
     * POST '/webforms-admin/forms'
     */
    public function it_creates_a_form()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
            ->json('POST', '/webforms-admin/forms', [
                'sort' => 2,
                'slug' => 'new-form',
                'menu_title' => 'New form title for the menu',
                'title' => 'New form title',
                'description' => 'An awesome new form',
            ])
            ->assertStatus(201);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'sort',
                'slug',
                'menu_title',
                'title',
                'description',
                'completed',
            ],
        ]);

        $form = Form::where('slug', 'new-form')->first();
        $this->assertEquals($form->id, $response->json('data.id'));
        $this->assertEquals(2, $response->json('data.sort'));
        $this->assertEquals('new-form', $response->json('data.slug'));
        $this->assertEquals('New form title for the menu', $response->json('data.menu_title'));
        $this->assertEquals('New form title', $response->json('data.title'));
        $this->assertEquals('An awesome new form', $response->json('data.description'));
        $this->assertEquals(true, $response->json('data.completed'));
    }

    /**
     * @test
     * POST '/webforms-admin/forms'
     */
    public function it_validates_uniqueness_of_the_slug_when_it_creates_a_new_form()
    {
        $user = factory(User::class)->create();

        factory(Form::class)->create([
            'slug' => 'new-form-title',
        ]);

        $this->actingAs($user)
            ->json('POST', '/webforms-admin/forms/', [
                'slug' => 'new-form-title',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('slug');
    }

    /**
     * @test
     * POST '/webforms-admin/forms'
     */
    public function it_creates_a_slug_for_a_form()
    {
        $user = factory(User::class)->create();

        factory(Form::class)->create([
            'slug' => 'new-form-title',
        ]);

        factory(Form::class)->create([
            'slug' => 'new-form-title-1',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms-admin/forms', [
                'sort' => 3,
                'title' => 'New form title',
            ])
            ->assertStatus(201);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'sort',
                'slug',
                'menu_title',
                'title',
                'description',
                'completed',
            ],
        ]);

        $form = Form::where('slug', 'new-form-title-2')->first();
        $this->assertEquals($form->id, $response->json('data.id'));
        $this->assertEquals('new-form-title-2', $response->json('data.slug'));
    }

    /**
     * @test
     * POST '/webforms-admin/forms'
     */
    public function it_creates_a_sort_for_a_form()
    {
        $user = factory(User::class)->create();

        factory(Form::class)->create([
            'sort' => 1,
            'slug' => 'new-form-title',
        ]);

        factory(Form::class)->create([
            'sort' => 2,
            'slug' => 'new-form-title-1',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms-admin/forms', [
                'slug' => 'new-form',
                'title' => 'New form title',
            ])
            ->assertStatus(201);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'sort',
                'slug',
                'menu_title',
                'title',
                'description',
                'completed',
            ],
        ]);

        $form = Form::where('slug', 'new-form')->first();
        $this->assertEquals($form->id, $response->json('data.id'));
        $this->assertEquals(3, $response->json('data.sort'));
    }

    /**
     * @test
     * POST '/webforms-admin/forms'
     */
    public function sort_works_fine()
    {
        $user = factory(User::class)->create();

        $secondForm = factory(Form::class)->create([
            'sort' => 1,
            'slug' => 'new-form-title',
        ]);

        $thirdForm = factory(Form::class)->create([
            'sort' => 2,
            'slug' => 'new-form-title-1',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms-admin/forms', [
                'sort' => 1,
                'slug' => 'new-form',
                'title' => 'New form title',
            ])
            ->assertStatus(201);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'sort',
                'slug',
                'menu_title',
                'title',
                'description',
                'completed',
            ],
        ]);

        $form = Form::where('slug', 'new-form')->first();
        $this->assertEquals($form->id, $response->json('data.id'));
        $this->assertEquals(1, $response->json('data.sort'));
        $this->assertEquals(2, $secondForm->fresh()->sort);
        $this->assertEquals(3, $thirdForm->fresh()->sort);
    }
}
