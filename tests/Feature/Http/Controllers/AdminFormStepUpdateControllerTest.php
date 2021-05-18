<?php

namespace R64\Webforms\Tests\Feature\Http\Controllers;

use R64\Webforms\Models\Form;
use R64\Webforms\Models\FormStep;
use R64\Webforms\Tests\Feature\Models\User;
use R64\Webforms\Tests\TestCase;

class AdminFormStepUpdateControllerTest extends TestCase
{
    const FORM_STEP_STRUCTURE = [
        'id',
        'sort',
        'slug',
        'menu_title',
        'title',
        'description',
        'completed',
        'form' => [
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
     * PUT '/webforms-admin/form-steps/{formStep}'
     */
    public function it_updates_a_step()
    {
        $user = factory(User::class)->create();
        $form = factory(Form::class)->create();
        $formStep = factory(FormStep::class)->create([
            'form_id' => $form->id,
            'sort' => 2,
            'slug' => 'new-form-step',
            'menu_title' => 'New form step title for the menu',
            'title' => 'New form step title',
            'description' => 'An awesome new form step',
            'is_personal_data' => 0,
        ]);

        $response = $this->actingAs($user)
            ->json('PUT', '/webforms-admin/form-steps/' . $formStep->id, [
                'form_id' => $form->id,
                'sort' => 1,
                'slug' => 'edited-new-form-step',
                'menu_title' => 'Edited new form step title for the menu',
                'title' => 'Edited new form step title',
                'description' => 'Editing an awesome new form step',
                'is_personal_data' => 1,
            ])
            ->assertStatus(200);

        $response->assertJsonStructure([
            'data' => self::FORM_STEP_STRUCTURE,
        ]);

        $this->assertEquals($formStep->id, $response->json('data.id'));
        $this->assertEquals(1, $response->json('data.sort'));
        $this->assertEquals('edited-new-form-step', $response->json('data.slug'));
        $this->assertEquals('Edited new form step title for the menu', $response->json('data.menu_title'));
        $this->assertEquals('Edited new form step title', $response->json('data.title'));
        $this->assertEquals('Editing an awesome new form step', $response->json('data.description'));
        $this->assertEquals(1, $formStep->fresh()->is_personal_data);
    }

    /**
     * @test
     * PUT '/webforms-admin/form-steps/{formStep}'
     */
    public function it_can_update_only_the_title_of_a_step()
    {
        $user = factory(User::class)->create();
        $form = factory(Form::class)->create();
        $formStep = factory(FormStep::class)->create([
            'form_id' => $form->id,
            'sort' => 1,
            'slug' => 'new-form-step',
            'menu_title' => 'New form step title for the menu',
            'title' => 'New form step title',
            'description' => 'An awesome new form step',
            'is_personal_data' => 0,
        ]);

        $response = $this->actingAs($user)
            ->json('PUT', '/webforms-admin/form-steps/' . $formStep->id, [
                'title' => 'Edited new form step title',
            ])
            ->assertStatus(200);

        $response->assertJsonStructure([
            'data' => self::FORM_STEP_STRUCTURE,
        ]);

        $this->assertEquals($formStep->id, $response->json('data.id'));
        $this->assertEquals(1, $response->json('data.sort'));
        $this->assertEquals('new-form-step', $response->json('data.slug'));
        $this->assertEquals('New form step title for the menu', $response->json('data.menu_title'));
        $this->assertEquals('Edited new form step title', $response->json('data.title'));
        $this->assertEquals('An awesome new form step', $response->json('data.description'));
        $this->assertEquals(0, $formStep->fresh()->is_personal_data);
        $this->assertEquals($form->id, $formStep->fresh()->form_id);
    }

    /**
     * @test
     * PUT '/webforms-admin/form-steps/{formStep}'
     */
    public function it_validates_uniqueness_of_the_slug_when_it_updates_a_new_form_step()
    {
        $user = factory(User::class)->create();
        $form = factory(Form::class)->create();

        factory(FormStep::class)->create([
            'slug' => 'new-form-step-title',
        ]);

        $anotherFormStep = factory(FormStep::class)->create([
            'slug' => 'another-form-step-title',
        ]);

        $this->actingAs($user)
            ->json('PUT', '/webforms-admin/form-steps/' . $anotherFormStep->id, [
                'slug' => 'new-form-step-title',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('slug');
    }

    /**
     * @test
     * PUT '/webforms-admin/form-steps/{formStep}'
     */
    public function it_can_change_the_form_for_a_step()
    {
        $user = factory(User::class)->create();
        $form = factory(Form::class)->create();
        $anotherForm = factory(Form::class)->create();

        $formStep = factory(FormStep::class)->create([
            'form_id' => $form->id,
            'slug' => 'new-form-step-title',
        ]);

        $response = $this->actingAs($user)
            ->json('PUT', '/webforms-admin/form-steps/' . $formStep->id, [
                'form_id' => $anotherForm->id,
            ])
            ->assertStatus(200);

        $response->assertJsonStructure([
            'data' => self::FORM_STEP_STRUCTURE,
        ]);

        $this->assertEquals($formStep->id, $response->json('data.id'));
        $this->assertEquals($anotherForm->id, $formStep->fresh()->form_id);
    }

    /**
     * @test
     * PUT '/webforms-admin/form-steps/{formStep}'
     */
    public function it_can_change_the_sort_of_a_step()
    {
        $user = factory(User::class)->create();
        $form = factory(Form::class)->create();

        $firstFormStep = factory(FormStep::class)->create([
            'form_id' => $form->id,
            'sort' => 1,
            'slug' => 'first-new-form-step-title',
        ]);

        $secondFormStep = factory(FormStep::class)->create([
            'form_id' => $form->id,
            'sort' => 2,
            'slug' => 'second-new-form-step-title-1',
        ]);

        $thirdFormStep = factory(FormStep::class)->create([
            'form_id' => $form->id,
            'sort' => 3,
            'slug' => 'third-new-form-step-title-1',
        ]);

        $response = $this->actingAs($user)
            ->json('PUT', '/webforms-admin/form-steps/' . $thirdFormStep->id, [
                'sort' => 1,
            ])
            ->assertStatus(200);

        $response->assertJsonStructure([
            'data' => self::FORM_STEP_STRUCTURE,
        ]);

        $this->assertEquals($thirdFormStep->id, $response->json('data.id'));
        $this->assertEquals(1, $response->json('data.sort'));

        $this->assertEquals(1, $thirdFormStep->fresh()->sort);
        $this->assertEquals(2, $firstFormStep->fresh()->sort);
        $this->assertEquals(3, $secondFormStep->fresh()->sort);
    }
}
