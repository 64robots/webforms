<?php

namespace R64\Webforms\Tests\Feature\Http\Controllers;

use R64\Webforms\Models\FormSection;
use R64\Webforms\Tests\Feature\Models\User;
use R64\Webforms\Tests\TestCase;

class AdminFormSectionStoreControllerTest extends TestCase
{
    /**
     * @test
     * POST '/webforms-admin/form-sections'
     */
    public function it_creates_a_section()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
            ->json('POST', '/webforms-admin/form-sections', [
                'sort' => 2,
                'slug' => 'new-form-section',
                'menu_title' => 'New form section title for the menu',
                'title' => 'New form section title',
                'description' => 'An awesome new form section',
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

        $formSection = FormSection::where('slug', 'new-form-section')->first();
        $this->assertEquals($formSection->id, $response->json('data.id'));
        $this->assertEquals(2, $response->json('data.sort'));
        $this->assertEquals('new-form-section', $response->json('data.slug'));
        $this->assertEquals('New form section title for the menu', $response->json('data.menu_title'));
        $this->assertEquals('New form section title', $response->json('data.title'));
        $this->assertEquals('An awesome new form section', $response->json('data.description'));
        $this->assertEquals(true, $response->json('data.completed'));
    }

    /**
     * @test
     * POST '/webforms-admin/form-sections'
     */
    public function it_validates_uniqueness_of_the_slug_when_it_creates_a_new_form_section()
    {
        $user = factory(User::class)->create();

        factory(FormSection::class)->create([
            'slug' => 'new-form-section-title',
        ]);

        $this->actingAs($user)
            ->json('POST', '/webforms-admin/form-sections/', [
                'slug' => 'new-form-section-title',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('slug');
    }

    /**
     * @test
     * POST '/webforms-admin/form-sections'
     */
    public function it_creates_a_slug_for_a_section()
    {
        $user = factory(User::class)->create();

        factory(FormSection::class)->create([
            'slug' => 'new-form-section-title',
        ]);

        factory(FormSection::class)->create([
            'slug' => 'new-form-section-title-1',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms-admin/form-sections', [
                'sort' => 3,
                'title' => 'New form section title',
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

        $formSection = FormSection::where('slug', 'new-form-section-title-2')->first();
        $this->assertEquals($formSection->id, $response->json('data.id'));
        $this->assertEquals('new-form-section-title-2', $response->json('data.slug'));
    }

    /**
     * @test
     * POST '/webforms-admin/form-sections'
     */
    public function it_creates_a_sort_for_a_section()
    {
        $user = factory(User::class)->create();

        factory(FormSection::class)->create([
            'sort' => 1,
            'slug' => 'new-form-section-title',
        ]);

        factory(FormSection::class)->create([
            'sort' => 2,
            'slug' => 'new-form-section-title-1',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms-admin/form-sections', [
                'slug' => 'new-form-section',
                'title' => 'New form section title',
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

        $formSection = FormSection::where('slug', 'new-form-section')->first();
        $this->assertEquals($formSection->id, $response->json('data.id'));
        $this->assertEquals(3, $response->json('data.sort'));
    }

    /**
     * @test
     * POST '/webforms-admin/form-sections'
     */
    public function sort_works_fine()
    {
        $user = factory(User::class)->create();

        $secondSection = factory(FormSection::class)->create([
            'sort' => 1,
            'slug' => 'new-form-section-title',
        ]);

        $thirdSection = factory(FormSection::class)->create([
            'sort' => 2,
            'slug' => 'new-form-section-title-1',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms-admin/form-sections', [
                'sort' => 1,
                'slug' => 'new-form-section',
                'title' => 'New form section title',
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

        $formSection = FormSection::where('slug', 'new-form-section')->first();
        $this->assertEquals($formSection->id, $response->json('data.id'));
        $this->assertEquals(1, $response->json('data.sort'));
        $this->assertEquals(2, $secondSection->fresh()->sort);
        $this->assertEquals(3, $thirdSection->fresh()->sort);
    }
}
