<?php

namespace R64\Webforms\Tests\Feature\Http\Controllers;

use R64\Webforms\Models\Form;
use R64\Webforms\Tests\Feature\Models\User;
use R64\Webforms\Tests\TestCase;

class AdminFormUpdateControllerTest extends TestCase
{
    /**
     * @test
     * PUT '/webforms-admin/forms/{form}'
     */
    public function it_updates_a_form()
    {
        $user = factory(User::class)->create();
        $form = factory(Form::class)->create([
            'sort' => 2,
            'slug' => 'new-form',
            'menu_title' => 'New form title for the menu',
            'title' => 'New form title',
            'description' => 'An awesome new form',
        ]);

        $response = $this->actingAs($user)
            ->json('PUT', '/webforms-admin/forms/' . $form->id, [
                'sort' => 1,
                'slug' => 'edited-new-form',
                'menu_title' => 'Edited new form title for the menu',
                'title' => 'Edited new form title',
                'description' => 'Editing an awesome new form',
            ])
            ->assertStatus(200);

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

        $this->assertEquals($form->id, $response->json('data.id'));
        $this->assertEquals(1, $response->json('data.sort'));
        $this->assertEquals('edited-new-form', $response->json('data.slug'));
        $this->assertEquals('Edited new form title for the menu', $response->json('data.menu_title'));
        $this->assertEquals('Edited new form title', $response->json('data.title'));
        $this->assertEquals('Editing an awesome new form', $response->json('data.description'));
        $this->assertEquals(true, $response->json('data.completed'));
    }

    /**
     * @test
     * PUT '/webforms-admin/forms/{form}'
     */
    public function it_can_updates_only_the_title()
    {
        $user = factory(User::class)->create();
        $form = factory(Form::class)->create([
            'sort' => 2,
            'slug' => 'new-form',
            'menu_title' => 'New form title for the menu',
            'title' => 'New form title',
            'description' => 'An awesome new form',
        ]);

        $response = $this->actingAs($user)
            ->json('PUT', '/webforms-admin/forms/' . $form->id, [
                'title' => 'Edited new form title',
            ])
            ->assertStatus(200);

        $this->assertEquals($form->id, $response->json('data.id'));
        $this->assertEquals(2, $response->json('data.sort'));
        $this->assertEquals('new-form', $response->json('data.slug'));
        $this->assertEquals('New form title for the menu', $response->json('data.menu_title'));
        $this->assertEquals('Edited new form title', $response->json('data.title'));
        $this->assertEquals('An awesome new form', $response->json('data.description'));
        $this->assertEquals(true, $response->json('data.completed'));
    }

    /**
     * @test
     * PUT '/webforms-admin/forms/{form}'
     */
    public function it_validates_uniqueness_of_the_slug()
    {
        $user = factory(User::class)->create();
        $form = factory(Form::class)->create([
            'slug' => 'form-title',
        ]);

        factory(Form::class)->create([
            'slug' => 'new-form-title',
        ]);

        $this->actingAs($user)
            ->json('PUT', '/webforms-admin/forms/' . $form->id, [
                'slug' => 'new-form-title',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('slug');

        $this->assertEquals('form-title', $form->fresh()->slug);
    }

    /**
     * @test
     * PUT '/webforms-admin/forms/{form}'
     */
    public function it_can_updates_only_the_sort_for_a_form()
    {
        $user = factory(User::class)->create();

        $firstForm = factory(Form::class)->create([
            'sort' => 1,
            'slug' => 'first-form-title',
        ]);

        $secondForm = factory(Form::class)->create([
            'sort' => 2,
            'slug' => 'second-form-title',
        ]);

        $thirdForm = factory(Form::class)->create([
            'sort' => 3,
            'slug' => 'third-form-title-1',
        ]);

        $response = $this->actingAs($user)
            ->json('PUT', '/webforms-admin/forms/' . $thirdForm->id, [
                'sort' => 1,
            ])
            ->assertStatus(200);

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

        $this->assertEquals($thirdForm->id, $response->json('data.id'));
        $this->assertEquals(1, $response->json('data.sort'));

        $this->assertEquals(1, $thirdForm->fresh()->sort);
        $this->assertEquals(2, $firstForm->fresh()->sort);
        $this->assertEquals(3, $secondForm->fresh()->sort);
    }
}
