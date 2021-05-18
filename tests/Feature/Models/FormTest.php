<?php

namespace R64\Webforms\Tests\Feature\Models;

use R64\Webforms\Models\Form;
use R64\Webforms\Models\FormStep;
use R64\Webforms\Tests\TestCase;

class FormTest extends TestCase
{
    /** @test */
    public function it_can_create_models()
    {
        /** @var Form $form */
        $form = factory(Form::class)->create([
            'title' => 'First Form',
            'slug' => 'first-form',
        ]);

        $this->assertDatabaseHas('forms', [
            'title' => 'First Form',
            'slug' => 'first-form',
        ]);
    }

    /** @test */
    public function it_can_be_associated_with_a_entity_using_formable()
    {
        $user = factory(User::class)->create();
        $form = factory(Form::class)->create();
        $user->associateForm($form);
        $this->assertTrue($user->form->is($form));
        $this->assertTrue($form->formable->is($user));
    }

    /** @test */
    public function menu_title_frontend_returns_title_if_it_is_null()
    {
        /** @var Form $form */
        $form = factory(Form::class)->create([
            'title' => 'First Form',
            'slug' => 'first-form',
        ]);

        $this->assertEquals('First Form', $form->menu_title_frontend);
    }

    /** @test */
    public function it_can_add_steps()
    {
        $form = factory(Form::class)->create();

        $formStep = factory(FormStep::class)->make();

        $form->addFormStep($formStep);

        $this->assertCount(1, $form->formSteps);
        $this->assertEquals($form->id, $form->formSteps->first()->id);
    }

    /** @test */
    public function it_can_remove_steps()
    {
        $form = factory(Form::class)->create();

        $formStep = factory(FormStep::class)->create([
            'form_id' => $form->id,
        ]);

        $anotherFormStep = factory(FormStep::class)->create([
            'form_id' => $form->id,
        ]);

        $this->assertCount(2, $form->formSteps);
        $this->assertEquals($formStep->id, $form->formSteps->sortBy('id')->first()->id);
        $this->assertEquals($anotherFormStep->id, $form->formSteps->sortBy('id')->last()->id);

        $form->removeFormStep($formStep);

        $form->refresh();
        $this->assertCount(1, $form->formSteps);
        $this->assertEquals($anotherFormStep->id, $form->formSteps->sortBy('id')->first()->id);
    }

    /** @test */
    public function it_knows_about_if_current_user_had_completed_it()
    {
        $user = factory(User::class)->create();
        $form = factory(Form::class)->create();
        $formStep = factory(FormStep::class)->create([
            'form_id' => $form->id,
        ]);
        $anotherFormStep = factory(FormStep::class)->create([
            'form_id' => $form->id,
        ]);
        $user->addFormSteps();
        $user->markFormStepAsCompleted($formStep);

        $this->actingAs($user);
        $this->assertFalse($form->refresh()->is_completed_by_current_user);

        $user->markFormStepAsCompleted($anotherFormStep);

        $this->assertTrue($form->refresh()->is_completed_by_current_user);
    }
}
