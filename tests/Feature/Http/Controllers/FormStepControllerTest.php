<?php

namespace R64\Webforms\Tests\Feature\Http\Controllers;

use R64\Webforms\Models\Form;
use R64\Webforms\Models\FormStep;
use R64\Webforms\Models\Question;
use R64\Webforms\Tests\Feature\Models\User;
use R64\Webforms\Tests\TestCase;

class FormStepControllerTest extends TestCase
{
    /**
     * @test
     * GET '/webforms/form-steps'
     */
    public function it_returns_user_form_steps()
    {
        $user = factory(User::class)->create();
        $form = factory(Form::class)->create();
        $secondFormStep = factory(FormStep::class)->create([
            'sort' => 2,
            'form_id' => $form->id,
        ]);
        $firstFormStep = factory(FormStep::class)->create([
            'sort' => 1,
            'form_id' => $form->id,
        ]);

        $user->addFormSteps();

        $response = $this->actingAs($user)->json('GET', '/webforms/form-steps')->assertOk();

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'form',
                    'sort',
                    'slug',
                    'menu_title',
                    'title',
                    'description',
                    'completed',
                ],
            ],
        ]);

        $this->assertCount(2, $response->json('data'));
        $this->assertEquals($firstFormStep->id, $response->json('data.0.id'));
        $this->assertEquals($secondFormStep->id, $response->json('data.1.id'));
    }

    /**
     * @test
     * PUT '/webforms/form-steps/{formStep}'
     */
    public function it_can_mark_fictional_answers_as_real_ones()
    {
        $user = factory(User::class)->create();
        $firstFormStep = factory(FormStep::class)->create(['sort' => 1]);
        $secondFormStep = factory(FormStep::class)->create(['sort' => 2]);
        $defaultValueQuestion = factory(Question::class)->create([
            'form_step_id' => $firstFormStep->id,
            'default_value' => '0',
        ]);
        $otherDefaultValueQuestion = factory(Question::class)->create([
            'form_step_id' => $secondFormStep->id,
            'default_value' => '0',
        ]);
        $user->addFormSteps();

        $fictionalAnswersForFirstStep = $firstFormStep->getFictionalAnswersFor($user);
        $this->assertNotEmpty($fictionalAnswersForFirstStep);
        $fictionalAnswersForSecondStep = $secondFormStep->getFictionalAnswersFor($user, $firstFormStep);
        $this->assertNotEmpty($fictionalAnswersForSecondStep);

        $response = $this->actingAs($user)
            ->json('PUT', '/webforms/form-steps/' . $firstFormStep->id)
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'sort',
                    'form',
                    'slug',
                    'menu_title',
                    'title',
                    'description',
                    'completed',
                ],
            ]);

        $this->assertEquals($firstFormStep->id, $response->json('data.id'));
        $fictionalAnswersForFirstStep = $firstFormStep->getFictionalAnswersFor($user);
        $this->assertEmpty($fictionalAnswersForFirstStep);
        $fictionalAnswersForSecondStep = $secondFormStep->getFictionalAnswersFor($user);
        $this->assertNotEmpty($fictionalAnswersForSecondStep);
    }
}
