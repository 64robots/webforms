<?php

namespace R64\Webforms\Tests\Feature\Http\Controllers;

use R64\Webforms\Models\Question;
use R64\Webforms\Models\QuestionTypes;
use R64\Webforms\Models\Step;
use R64\Webforms\Tests\TestCase;

class QuestionControllerTest extends TestCase
{
    /** @test */
    public function questions_can_be_listed()
    {
        $question = factory(Question::class)->create(['sort' => 2]);
        $question = factory(Question::class)->create(['sort' => 1]);

        $response = $this->json('GET', '/webforms/questions')
            ->assertOk();

        $this->assertCount(2, $response->json('data'));
        $this->assertEquals(1, $response->json('data.0.sort'));
        $this->assertEquals(2, $response->json('data.1.sort'));

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'step' => [
                        'id',
                        'sort',
                        'slug',
                        'menu_title',
                        'title',
                        'description',
                        // Todo
                        // 'completed',
                    ],
                    'sort',
                    'depends_on',
                    'showed_when',
                    'required',
                    'slug',
                    'group_by',
                    'group_by_description',
                    'label_position',
                    'help_title',
                    'help_body',
                    'type',
                    'post_input_text',
                    'title',
                    'description',
                    'default_value',
                    'options',
                    // Todo
                    // 'answer',
                ],
            ],
            'success',
        ]);
    }

    /** @test */
    public function options_question()
    {
        $question = factory(Question::class)->state('options')->create([
            'sort' => 1,
            'options' => [
                'a-option' => 'An option',
                'another-option' => 'Another option',
            ],
        ]);

        $response = $this->json('GET', '/webforms/questions')
            ->assertOk();

        $this->assertArrayNotHasKey('a-option', collect($response->json('data'))->where('id', $question->id)->first()['options']);
        $this->assertArrayNotHasKey('another-option', collect($response->json('data'))->where('id', $question->id)->first()['options']);
        $this->assertContains([
            'label' => 'Another option',
            'value' => 'another-option',
        ], collect($response->json('data'))->where('id', $question->id)->first()['options']);
        $this->assertContains([
            'label' => 'An option',
            'value' => 'a-option',
        ], collect($response->json('data'))->where('id', $question->id)->first()['options']);
    }

    /** @test */
    public function showed_when_question()
    {
        $question = factory(Question::class)
            ->state('options')
            ->create([
                'sort' => 1,
                'showed_when' => [true, 1, 2, 3],
            ]);

        $response = $this->json('GET', '/webforms/questions')
            ->assertOk();

        $this->assertEquals([true, 1, 2, 3], collect($response->json('data'))->where('id', $question->id)->first()['showed_when']);
    }

    /** @test */
    public function default_value_question()
    {
        $question = factory(Question::class)->state('options')->create([
            'sort' => 1,
            'type' => QuestionTypes::AGE_TYPE,
            'default_value' => '65',
        ]);

        $response = $this->json('GET', '/webforms/questions')
            ->assertOk();

        $this->assertSame(65, collect($response->json('data'))->where('id', $question->id)->first()['default_value']);
    }

    /**
     * @test
     * GET 'api/questions?form_step=1'
     */
    public function it_can_filter_questions_by_step()
    {
        $formStep = factory(Step::class)->create();
        $otherFormStep = factory(Step::class)->create();

        $questionForFormStep = factory(Question::class)->create([
            'step_id' => $formStep->id,
            'sort' => 1,
        ]);
        $otherQuestionForFormStep = factory(Question::class)->create([
            'step_id' => $formStep->id,
            'sort' => 2,
        ]);
        $questionForOtherFormStep = factory(Question::class)->create([
            'step_id' => $otherFormStep->id,
        ]);

        $response = $this->json('GET', '/webforms/questions?step=' . $formStep->id)
            ->assertOk();

        $this->assertCount(2, $response->json('data'));
        $this->assertSame($questionForFormStep->id, $response->json('data.0.id'));
        $this->assertSame($otherQuestionForFormStep->id, $response->json('data.1.id'));
    }

//    /**
//     * @test
//     * GET 'api/questions'
//     */
//    public function questions_with_answers_for_a_question()
//    {
//        $user = factory(User::class)->state('confirmed-data')->create();
//        $formStep = factory(FormStep::class)->create();
//        $user->addFormSteps($formStep);
//
//        $question = factory(Question::class)->state('text')->create([
//            'sort' => 1,
//            'form_step_id' => $formStep->id
//        ]);
//        factory(Answer::class)->create([
//            'user_id' => $user->id,
//            'question_id' => $question->id,
//            'revision' => 1,
//            'is_current' => 0,
//            'text' => 'This is an answer',
//            'created_at' => '2010-01-01 10:00:00'
//        ]);
//        $answer = factory(Answer::class)->create([
//            'user_id' => $user->id,
//            'question_id' => $question->id,
//            'revision' => 2,
//            'is_current' => 1,
//            'text' => 'This is the last answer',
//            'created_at' => '2010-01-01 09:00:05'
//        ]);
//
//        $otherQuestion = factory(Question::class)->state('boolean')->create([
//            'sort' => 2,
//            'form_step_id' => $formStep->id
//        ]);
//        $answerForOtherQuestion = factory(Answer::class)->create([
//            'user_id' => $user->id,
//            'question_id' => $otherQuestion->id,
//            'text' => '1',
//            'created_at' => '2010-01-01 10:00:00'
//        ]);
//
//        $response = $this->actingAs($user, 'sanctum')
//            ->json('GET', '/api/questions')
//            ->assertOk();
//
//        tap(collect($response->json('data'))->where('id', $question->id)->first()['answer'], function ($responseAnswer) use ($answer, $user, $question) {
//            $this->assertEquals($answer->id, $responseAnswer['id']);
//            $this->assertEquals($user->id, $responseAnswer['user_id']);
//            $this->assertEquals($question->id, $responseAnswer['question_id']);
//            $this->assertEquals('This is the last answer', $responseAnswer['text']);
//        });
//
//        tap(collect($response->json('data'))->where('id', $otherQuestion->id)->first()['answer'], function ($responseAnswer) use ($answerForOtherQuestion, $user, $otherQuestion) {
//            $this->assertEquals($answerForOtherQuestion->id, $responseAnswer['id']);
//            $this->assertEquals($user->id, $responseAnswer['user_id']);
//            $this->assertEquals($otherQuestion->id, $responseAnswer['question_id']);
//            $this->assertSame(true, $responseAnswer['text']);
//        });
//
//        $response->assertJsonStructure([
//            'id',
//            'form_step' => [
//                'id',
//                'sort',
//                'slug',
//                'title',
//                'description',
//                'completed',
//            ],
//            'sort',
//            'depends_on',
//            'showed_when',
//            'required',
//            'slug',
//            'group_by',
//            'group_by_description',
//            'label_position',
//            'help_title',
//            'help_body',
//            'type',
//            'post_input_text',
//            'title',
//            'description',
//            'default_value',
//            'options',
//            'answer' => [
//                'id',
//                'question_id',
//                'user_id',
//                'text',
//                'confirmed',
//            ],
//        ], collect($response->json('data'))->where('id', $question->id)->first());
//    }
}
