<?php

namespace R64\Webforms\Tests\Feature\Models;

use R64\Webforms\Models\Answer;
use R64\Webforms\Models\Form;
use R64\Webforms\Models\FormStep;
use R64\Webforms\Models\Question;
use R64\Webforms\Tests\TestCase;

class FormStepTest extends TestCase
{
    /** @test */
    public function it_can_create_models()
    {
        /** @var Form $form */
        $form = factory(Form::class)->create();

        /** @var FormStep $formStep */
        $formStep = factory(FormStep::class)->create([
            'form_id' => $form->id,
            'title' => 'First Step',
            'slug' => 'first-step',
            'sort' => 1,
        ]);

        $this->assertDatabaseHas('form_steps', [
            'title' => 'First Step',
            'slug' => 'first-step',
            'sort' => 1,
        ]);
    }

    /** @test */
    public function menu_title_frontend_returns_title_if_it_is_null()
    {
        /** @var FormStep $formStep */
        $formStep = factory(FormStep::class)->create([
            'title' => 'First Step',
            'slug' => 'first-step',
        ]);

        $this->assertEquals('First Step', $formStep->menu_title_frontend);
    }

    /** @test */
    public function it_belongs_to_a_form()
    {
        /** @var Form $form */
        $form = factory(Form::class)->create();
        /** @var Form $anotherForm */
        $anotherForm = factory(Form::class)->create();
        /** @var FormStep $formStep */
        $formStep = factory(FormStep::class)->make();

        $formStep->associateForm($form);

        $this->assertEquals($form->id, $formStep->form_id);

        $formStep->associateForm($anotherForm);

        $this->assertEquals($anotherForm->id, $formStep->refresh()->form_id);
    }

    /** @test */
    public function form_step_knows_if_it_is_completed_by_current_user()
    {
        $formStep = factory(FormStep::class)->create();
        $user = factory(User::class)->create();
        $user->formSteps()->attach($formStep);

        $this->actingAs($user);

        $this->assertFalse($formStep->is_completed_by_current_user);

        $user->markFormStepAsCompleted($formStep);

        $this->assertTrue($formStep->fresh()->is_completed_by_current_user);
    }

    /** @test */
    public function it_can_mark_user_answer_as_real()
    {
        $formStep = factory(FormStep::class)->create();

        $question = factory(Question::class)->state('text')->create([
            'form_step_id' => $formStep->id,
            'required' => true,
        ]);

        $user = factory(User::class)->create();

        $user->formSteps()->attach($formStep);

        $answer = factory(Answer::class)->create([
            'question_id' => $question->id,
            'user_id' => $user->id,
            'is_real' => false,
        ]);

        $this->actingAs($user);

        $this->assertNotEmpty($formStep->getFictionalAnswersFor($user));
        $this->assertFalse($formStep->is_completed_by_current_user);

        $formStep->markFictionalAnswersAsRealFor($user);

        $this->assertEmpty($formStep->getFictionalAnswersFor($user));
        $this->assertTrue($formStep->fresh()->is_completed_by_current_user);
    }
}
