<?php

namespace R64\Webforms\Tests\Feature\Http\Controllers;

use R64\Webforms\Models\FormSection;
use R64\Webforms\Models\FormStep;
use R64\Webforms\Tests\Feature\Models\User;
use R64\Webforms\Tests\TestCase;

class AdminFormStepStoreControllerTest extends TestCase
{
    const FORM_STEP_STRUCTURE = [
        'id',
        'sort',
        'slug',
        'menu_title',
        'title',
        'description',
        'completed',
        'form_section' => [
            'id',
            'sort',
            'slug',
            'menu_title',
            'title',
            'description',
            'completed',
        ],
    ];

    /**
     * @test
     * POST '/webforms-admin/form-steps'
     */
    public function it_creates_a_step()
    {
        $user = factory(User::class)->create();
        $formSection = factory(FormSection::class)->create();

        $response = $this->actingAs($user)
            ->json('POST', '/webforms-admin/form-steps', [
                'form_section_id' => $formSection->id,
                'sort' => 2,
                'slug' => 'new-form-step',
                'menu_title' => 'New form step title for the menu',
                'title' => 'New form step title',
                'description' => 'An awesome new form step',
                'is_personal_data' => 1,
            ])
            ->assertStatus(201);

        $response->assertJsonStructure([
            'data' => self::FORM_STEP_STRUCTURE,
        ]);

        $formStep = FormStep::where('slug', 'new-form-step')->first();
        $this->assertEquals($formStep->id, $response->json('data.id'));
        $this->assertEquals(2, $response->json('data.sort'));
        $this->assertEquals('new-form-step', $response->json('data.slug'));
        $this->assertEquals('New form step title for the menu', $response->json('data.menu_title'));
        $this->assertEquals('New form step title', $response->json('data.title'));
        $this->assertEquals('An awesome new form step', $response->json('data.description'));
        $this->assertEquals(1, $formStep->is_personal_data);
    }

    /**
     * @test
     * POST '/webforms-admin/form-steps'
     */
    public function it_creates_a_slug_for_a_step()
    {
        $user = factory(User::class)->create();
        $formSection = factory(FormSection::class)->create();

        factory(FormStep::class)->create([
            'form_section_id' => $formSection->id,
            'slug' => 'new-form-step-title',
        ]);

        factory(FormStep::class)->create([
            'form_section_id' => $formSection->id,
            'slug' => 'new-form-step-title-1',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms-admin/form-steps', [
                'form_section_id' => $formSection->id,
                'sort' => 3,
                'title' => 'New form step title',
            ])
            ->assertStatus(201);

        $response->assertJsonStructure([
            'data' => self::FORM_STEP_STRUCTURE,
        ]);

        $formStep = FormStep::where('slug', 'new-form-step-title-2')->first();
        $this->assertEquals($formStep->id, $response->json('data.id'));
        $this->assertEquals('new-form-step-title-2', $response->json('data.slug'));
        $this->assertEquals(0, $formStep->is_personal_data);
    }

    /**
     * @test
     * POST '/webforms-admin/form-steps'
     */
    public function it_creates_a_sort_for_a_step()
    {
        $user = factory(User::class)->create();
        $formSection = factory(FormSection::class)->create();

        factory(FormStep::class)->create([
            'form_section_id' => $formSection->id,
            'sort' => 1,
            'slug' => 'new-form-step-title',
        ]);

        factory(FormStep::class)->create([
            'form_section_id' => $formSection->id,
            'sort' => 2,
            'slug' => 'new-form-step-title-1',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms-admin/form-steps', [
                'form_section_id' => $formSection->id,
                'slug' => 'new-form-step',
                'title' => 'New form step title',
            ])
            ->assertStatus(201);

        $response->assertJsonStructure([
            'data' => self::FORM_STEP_STRUCTURE,
        ]);

        $formStep = FormStep::where('slug', 'new-form-step')->first();
        $this->assertEquals($formStep->id, $response->json('data.id'));
        $this->assertEquals(3, $response->json('data.sort'));
    }

    /**
     * @test
     * POST '/webforms-admin/form-steps'
     */
    public function sort_works_fine()
    {
        $user = factory(User::class)->create();
        $formSection = factory(FormSection::class)->create();

        $secondStep = factory(FormStep::class)->create([
            'form_section_id' => $formSection->id,
            'sort' => 1,
            'slug' => 'new-form-step-title',
        ]);

        $thirdStep = factory(FormStep::class)->create([
            'form_section_id' => $formSection->id,
            'sort' => 2,
            'slug' => 'new-form-step-title-1',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms-admin/form-steps', [
                'form_section_id' => $formSection->id,
                'sort' => 1,
                'slug' => 'new-form-step',
                'title' => 'New form step title',
            ])
            ->assertStatus(201);

        $response->assertJsonStructure([
            'data' => self::FORM_STEP_STRUCTURE,
        ]);

        $formStep = FormStep::where('slug', 'new-form-step')->first();
        $this->assertEquals($formStep->id, $response->json('data.id'));
        $this->assertEquals(1, $response->json('data.sort'));
        $this->assertEquals(2, $secondStep->fresh()->sort);
        $this->assertEquals(3, $thirdStep->fresh()->sort);
    }
}
