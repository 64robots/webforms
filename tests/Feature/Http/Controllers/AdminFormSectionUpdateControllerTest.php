<?php

namespace R64\Webforms\Tests\Feature\Http\Controllers;

use R64\Webforms\Models\FormSection;
use R64\Webforms\Tests\Feature\Models\User;
use R64\Webforms\Tests\TestCase;

class AdminFormSectionUpdateControllerTest extends TestCase
{
    /**
     * @test
     * PUT '/webforms-admin/form-sections/{formSection}'
     */
    public function it_updates_a_section()
    {
        $user = factory(User::class)->create();
        $formSection = factory(FormSection::class)->create([
            'sort' => 2,
            'slug' => 'new-form-section',
            'menu_title' => 'New form section title for the menu',
            'title' => 'New form section title',
            'description' => 'An awesome new form section',
        ]);

        $response = $this->actingAs($user)
            ->json('PUT', '/webforms-admin/form-sections/' . $formSection->id, [
                'sort' => 1,
                'slug' => 'edited-new-form-section',
                'menu_title' => 'Edited new form section title for the menu',
                'title' => 'Edited new form section title',
                'description' => 'Editing an awesome new form section',
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

        $this->assertEquals($formSection->id, $response->json('data.id'));
        $this->assertEquals(1, $response->json('data.sort'));
        $this->assertEquals('edited-new-form-section', $response->json('data.slug'));
        $this->assertEquals('Edited new form section title for the menu', $response->json('data.menu_title'));
        $this->assertEquals('Edited new form section title', $response->json('data.title'));
        $this->assertEquals('Editing an awesome new form section', $response->json('data.description'));
        $this->assertEquals(true, $response->json('data.completed'));
    }

    /**
     * @test
     * PUT '/webforms-admin/form-sections/{formSection}'
     */
    public function it_can_updates_only_the_title()
    {
        $user = factory(User::class)->create();
        $formSection = factory(FormSection::class)->create([
            'sort' => 2,
            'slug' => 'new-form-section',
            'menu_title' => 'New form section title for the menu',
            'title' => 'New form section title',
            'description' => 'An awesome new form section',
        ]);

        $response = $this->actingAs($user)
            ->json('PUT', '/webforms-admin/form-sections/' . $formSection->id, [
                'title' => 'Edited new form section title',
            ])
            ->assertStatus(200);

        $this->assertEquals($formSection->id, $response->json('data.id'));
        $this->assertEquals(2, $response->json('data.sort'));
        $this->assertEquals('new-form-section', $response->json('data.slug'));
        $this->assertEquals('New form section title for the menu', $response->json('data.menu_title'));
        $this->assertEquals('Edited new form section title', $response->json('data.title'));
        $this->assertEquals('An awesome new form section', $response->json('data.description'));
        $this->assertEquals(true, $response->json('data.completed'));
    }

    /**
     * @test
     * PUT '/webforms-admin/form-sections/{formSection}'
     */
    public function it_validates_uniqueness_of_the_slug()
    {
        $user = factory(User::class)->create();
        $formSection = factory(FormSection::class)->create([
            'slug' => 'form-section-title',
        ]);

        factory(FormSection::class)->create([
            'slug' => 'new-form-section-title',
        ]);

        $this->actingAs($user)
            ->json('PUT', '/webforms-admin/form-sections/' . $formSection->id, [
                'slug' => 'new-form-section-title',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('slug');

        $this->assertEquals('form-section-title', $formSection->fresh()->slug);
    }

    /**
     * @test
     * PUT '/webforms-admin/form-sections/{formSection}'
     */
    public function it_can_updates_only_the_sort_for_a_section()
    {
        $user = factory(User::class)->create();

        $firstFormSection = factory(FormSection::class)->create([
            'sort' => 1,
            'slug' => 'first-form-section-title',
        ]);

        $secondFormSection = factory(FormSection::class)->create([
            'sort' => 2,
            'slug' => 'second-form-section-title',
        ]);

        $thirdFormSection = factory(FormSection::class)->create([
            'sort' => 3,
            'slug' => 'third-form-section-title-1',
        ]);

        $response = $this->actingAs($user)
            ->json('PUT', '/webforms-admin/form-sections/' . $thirdFormSection->id, [
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

        $this->assertEquals($thirdFormSection->id, $response->json('data.id'));
        $this->assertEquals(1, $response->json('data.sort'));

        $this->assertEquals(1, $thirdFormSection->fresh()->sort);
        $this->assertEquals(2, $firstFormSection->fresh()->sort);
        $this->assertEquals(3, $secondFormSection->fresh()->sort);
    }
}
