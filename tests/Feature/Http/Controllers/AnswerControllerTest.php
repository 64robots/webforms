<?php

namespace R64\Webforms\Tests\Feature\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use R64\Webforms\Events\AnswerCreated;
use R64\Webforms\Models\Answer;
use R64\Webforms\Models\Question;
use R64\Webforms\Tests\Feature\Models\User;
use R64\Webforms\Tests\TestCase;

class AnswerControllerTest extends TestCase
{
    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_can_create_an_answer_for_text_question()
    {
        $user = factory(User::class)->create();
        $textQuestion = factory(Question::class)->state('text')->create();
        $user->addFormSteps();

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $textQuestion->id,
                'text' => 'A text for this answer',
            ])->assertOk();

        $this->assertCount(1, $textQuestion->currentUserAnswers);
        $this->assertSame('A text for this answer', $textQuestion->current_user_answer->text);
        $this->assertSame(true, $textQuestion->current_user_answer->is_real);

        $this->assertSame('A text for this answer', $response->json('data.answer.text'));
        $response->assertJsonStructure([
            'data' => [
                'id',
                'form_step' => [
                    'id',
                    'sort',
                    'slug',
                    'title',
                    'description',
                    'completed',
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
                'answer' => [
                    'id',
                    'question_id',
                    'user_id',
                    'text',
                    'confirmed',
                ],
            ],
        ]);
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_can_update_an_answer_for_a_text_question()
    {
        $user = factory(User::class)->create();
        $textQuestion = factory(Question::class)->state('text')->create();
        $user->addFormSteps();
        $answer = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $textQuestion->id,
            'text' => 'A new text for this answer',
            'is_real' => false,
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $textQuestion->id,
                'text' => 'A new text for this answer',
            ])->assertOk();

        $this->assertSame('A new text for this answer', $response->json('data.answer.text'));

        $this->assertCount(1, $textQuestion->currentUserAnswers);
        $this->assertSame('A new text for this answer', $textQuestion->current_user_answer->text);
        $this->assertSame(true, $textQuestion->current_user_answer->is_real);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'form_step' => [
                    'id',
                    'sort',
                    'slug',
                    'title',
                    'description',
                    'completed',
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
                'answer' => [
                    'id',
                    'question_id',
                    'user_id',
                    'text',
                    'confirmed',
                ],
            ],
        ]);
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_can_create_an_answer_for_boolean_question()
    {
        $user = factory(User::class)->create();
        $booleanQuestion = factory(Question::class)->state('boolean')->create();
        $user->addFormSteps();

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $booleanQuestion->id,
                'text' => true,
            ])->assertOk();

        $this->assertCount(1, $booleanQuestion->currentUserAnswers);
        $this->assertSame(true, $booleanQuestion->current_user_answer->text);

        $this->assertSame(true, $response->json('data.answer.text'));
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_can_update_an_answer_for_a_boolean_question()
    {
        $user = factory(User::class)->create();
        $booleanQuestion = factory(Question::class)->state('boolean')->create();
        $user->addFormSteps();
        $answer = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $booleanQuestion->id,
            'text' => '1',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $booleanQuestion->id,
                'text' => false,
            ])->assertOk();

        $this->assertCount(1, $booleanQuestion->currentUserAnswers);
        $this->assertSame(false, $booleanQuestion->current_user_answer->text);

        $this->assertSame(false, $response->json('data.answer.text'));
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_cannot_update_an_answer_for_a_boolean_question_with_random_text()
    {
        $user = factory(User::class)->create();
        $booleanQuestion = factory(Question::class)->state('boolean')->create();
        $user->addFormSteps();
        $answer = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $booleanQuestion->id,
            'text' => 'true',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $booleanQuestion->id,
                'text' => 'random',
            ])->assertStatus(422);
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_can_create_an_answer_for_integer_question()
    {
        $user = factory(User::class)->create();
        $integerQuestion = factory(Question::class)->state('integer')->create();
        $user->addFormSteps();

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $integerQuestion->id,
                'text' => '123456',
            ])->assertOk();

        $this->assertCount(1, $integerQuestion->currentUserAnswers);
        $this->assertSame(123456, $integerQuestion->current_user_answer->text);

        $this->assertSame(123456, $response->json('data.answer.text'));
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_can_update_an_answer_for_a_integer_question()
    {
        $user = factory(User::class)->create();
        $integerQuestion = factory(Question::class)->state('integer')->create();
        $user->addFormSteps();
        $answer = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $integerQuestion->id,
            'text' => '123',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $integerQuestion->id,
                'text' => 123456,
            ])->assertOk();

        $this->assertCount(1, $integerQuestion->currentUserAnswers);
        $this->assertSame(123456, $integerQuestion->current_user_answer->text);

        $this->assertSame(123456, $response->json('data.answer.text'));
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_cannot_update_an_answer_for_a_integer_question_with_random_text()
    {
        $user = factory(User::class)->create();
        $integerQuestion = factory(Question::class)->state('integer')->create();
        $user->addFormSteps();
        $answer = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $integerQuestion->id,
            'text' => '123',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $integerQuestion->id,
                'text' => 'random',
            ])->assertStatus(422);
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_can_create_an_answer_for_date_question()
    {
        $user = factory(User::class)->create();
        $dateQuestion = factory(Question::class)->state('date')->create();
        $user->addFormSteps();

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $dateQuestion->id,
                'text' => '2010-01-01',
            ])->assertOk();

        $this->assertCount(1, $dateQuestion->currentUserAnswers);
        $this->assertEquals(Carbon::parse('2010-01-01'), $dateQuestion->current_user_answer->text);

        $this->assertSame('2010-01-01', $response->json('data.answer.text'));
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_can_update_an_answer_for_a_date_question()
    {
        $user = factory(User::class)->create();
        $dateQuestion = factory(Question::class)->state('date')->create();
        $user->addFormSteps();
        $answer = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $dateQuestion->id,
            'text' => '2010-01-01',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $dateQuestion->id,
                'text' => '2010-01-02',
            ])->assertOk();

        $this->assertCount(1, $dateQuestion->currentUserAnswers);
        $this->assertEquals(Carbon::parse('2010-01-02'), $dateQuestion->current_user_answer->text);

        $this->assertSame('2010-01-02', $response->json('data.answer.text'));
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_cannot_update_an_answer_for_a_date_question_with_random_text()
    {
        $user = factory(User::class)->create();
        $dateQuestion = factory(Question::class)->state('date')->create();
        $user->addFormSteps();
        $answer = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $dateQuestion->id,
            'text' => '2010-01-01',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $dateQuestion->id,
                'text' => '2010-20-01',
            ])->assertStatus(422);
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_can_create_an_answer_for_year_month_question()
    {
        $user = factory(User::class)->create();
        $yearMonthQuestion = factory(Question::class)->state('year-month')->create();
        $user->addFormSteps();

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $yearMonthQuestion->id,
                'text' => '2010-01',
            ])->assertOk();

        $this->assertCount(1, $yearMonthQuestion->currentUserAnswers);
        $this->assertEquals(Carbon::parse('2010-01-01 00:00:00'), $yearMonthQuestion->current_user_answer->text);

        $this->assertSame('2010-01', $response->json('data.answer.text'));
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_can_update_an_answer_for_a_year_month_question()
    {
        $user = factory(User::class)->create();
        $yearMonthQuestion = factory(Question::class)->state('year-month')->create();
        $user->addFormSteps();
        $answer = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $yearMonthQuestion->id,
            'text' => '2010-01',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $yearMonthQuestion->id,
                'text' => '2010-02',
            ])->assertOk();

        $this->assertCount(1, $yearMonthQuestion->currentUserAnswers);
        $this->assertEquals(Carbon::parse('2010-02'), $yearMonthQuestion->current_user_answer->text);

        $this->assertSame('2010-02', $response->json('data.answer.text'));
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_cannot_update_an_answer_for_a_year_month_question_with_random_text()
    {
        $user = factory(User::class)->create();
        $yearMonthQuestion = factory(Question::class)->state('year-month')->create();
        $user->addFormSteps();
        $answer = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $yearMonthQuestion->id,
            'text' => '2010-01',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $yearMonthQuestion->id,
                'text' => '2010-20',
            ])->assertStatus(422);
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_can_create_an_answer_for_options_question()
    {
        $user = factory(User::class)->create();
        $optionsQuestion = factory(Question::class)->state('options')->create([
            'options' => [
                'an-option' => 'An option',
                'another-option' => 'Another option',
            ],
        ]);
        $user->addFormSteps();

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $optionsQuestion->id,
                'text' => 'an-option',
            ])->assertOk();

        $this->assertCount(1, $optionsQuestion->currentUserAnswers);
        $this->assertSame('an-option', $optionsQuestion->current_user_answer->text);

        $this->assertSame('an-option', $response->json('data.answer.text'));
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_can_update_an_answer_for_an_options_question()
    {
        $user = factory(User::class)->create();
        $optionsQuestion = factory(Question::class)->state('options')->create([
            'options' => [
                'an-option' => 'An option',
                'other-option' => 'Other option',
            ],
        ]);
        $user->addFormSteps();
        $answer = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $optionsQuestion->id,
            'text' => 'an-option',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $optionsQuestion->id,
                'text' => 'other-option',
            ])->assertOk();

        $this->assertCount(1, $optionsQuestion->currentUserAnswers);
        $this->assertSame('other-option', $optionsQuestion->current_user_answer->text);

        $this->assertSame('other-option', $response->json('data.answer.text'));
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_cannot_update_an_answer_for_an_options_question_with_random_text()
    {
        $user = factory(User::class)->create();
        $optionsQuestion = factory(Question::class)->state('options')->create([
            'options' => [
                'an-option' => 'An option',
                'other-option' => 'Other option',
            ],
        ]);
        $user->addFormSteps();
        $answer = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $optionsQuestion->id,
            'text' => 'an option',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $optionsQuestion->id,
                'text' => 'a-non-valid-option',
            ])->assertStatus(422);
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_can_create_an_answer_for_percent_question()
    {
        $user = factory(User::class)->create();
        $percentQuestion = factory(Question::class)->state('percent')->create();
        $user->addFormSteps();

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $percentQuestion->id,
                'text' => '95',
            ])->assertOk();

        $this->assertCount(1, $percentQuestion->currentUserAnswers);
        $this->assertSame(95, $percentQuestion->current_user_answer->text);

        $this->assertSame(95, $response->json('data.answer.text'));
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_can_update_an_answer_for_a_percent_question()
    {
        $user = factory(User::class)->create();
        $percentQuestion = factory(Question::class)->state('percent')->create();
        $user->addFormSteps();
        $answer = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $percentQuestion->id,
            'text' => '95',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $percentQuestion->id,
                'text' => '100',
            ])->assertOk();

        $this->assertCount(1, $percentQuestion->currentUserAnswers);
        $this->assertSame(100, $percentQuestion->current_user_answer->text);

        $this->assertSame(100, $response->json('data.answer.text'));
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_cannot_update_an_answer_for_a_percent_question_with_random_text()
    {
        $user = factory(User::class)->create();
        $percentQuestion = factory(Question::class)->state('percent')->create();
        $user->addFormSteps();
        $answer = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $percentQuestion->id,
            'text' => '95',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $percentQuestion->id,
                'text' => '120',
            ])->assertStatus(422);
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_can_create_an_answer_for_money_question()
    {
        $user = factory(User::class)->create();
        $moneyQuestion = factory(Question::class)->state('money')->create();
        $user->addFormSteps();

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $moneyQuestion->id,
                'text' => '10000',
            ])->assertOk();

        $this->assertCount(1, $moneyQuestion->currentUserAnswers);
        $this->assertSame(10000, $moneyQuestion->current_user_answer->text);

        $this->assertSame(10000, $response->json('data.answer.text'));
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_can_update_an_answer_for_a_money_question()
    {
        $user = factory(User::class)->create();
        $moneyQuestion = factory(Question::class)->state('money')->create();
        $user->addFormSteps();
        $answer = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $moneyQuestion->id,
            'text' => '10000',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $moneyQuestion->id,
                'text' => '12000',
            ])->assertOk();

        $this->assertCount(1, $moneyQuestion->currentUserAnswers);
        $this->assertSame(12000, $moneyQuestion->current_user_answer->text);

        $this->assertSame(12000, $response->json('data.answer.text'));
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_cannot_update_an_answer_for_a_money_question_with_random_text()
    {
        $user = factory(User::class)->create();
        $moneyQuestion = factory(Question::class)->state('money')->create();
        $user->addFormSteps();
        $answer = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $moneyQuestion->id,
            'text' => '10000',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $moneyQuestion->id,
                'text' => 'A non numeric text',
            ])->assertStatus(422);
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_can_create_an_answer_for_an_email_question()
    {
        $user = factory(User::class)->create();
        $emailQuestion = factory(Question::class)->state('email')->create();
        $user->addFormSteps();

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $emailQuestion->id,
                'text' => 'anEmail@example.com',
            ])->assertOk();

        $this->assertCount(1, $emailQuestion->currentUserAnswers);
        $this->assertSame('anEmail@example.com', $emailQuestion->current_user_answer->text);
        $this->assertFalse($emailQuestion->current_user_answer->confirmed);

        $this->assertSame('anEmail@example.com', $response->json('data.answer.text'));
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function an_answer_for_a_email_question_launches_an_event()
    {
        Event::fake(AnswerCreated::class);
        $user = factory(User::class)->create();
        $emailQuestion = factory(Question::class)->state('email')->create();
        $user->addFormSteps();

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $emailQuestion->id,
                'text' => 'anEmail@example.com',
            ])->assertOk();

        $this->assertCount(1, $emailQuestion->currentUserAnswers);
        $this->assertSame('anEmail@example.com', $emailQuestion->current_user_answer->text);
        $this->assertFalse($emailQuestion->current_user_answer->confirmed);

        $this->assertSame('anEmail@example.com', $response->json('data.answer.text'));
        Event::assertDispatchedTimes(AnswerCreated::class, 1);
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_can_update_an_answer_for_an_email_question()
    {
        Event::fake(AnswerCreated::class);
        $user = factory(User::class)->create();
        $emailQuestion = factory(Question::class)->state('email')->create();
        $user->addFormSteps();
        $answer = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $emailQuestion->id,
            'text' => 'anEmail@example.com',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $emailQuestion->id,
                'text' => 'anotherEmail@example.com',
            ])->assertOk();

        $this->assertCount(1, $emailQuestion->currentUserAnswers);
        $this->assertSame('anotherEmail@example.com', $emailQuestion->current_user_answer->text);

        $this->assertSame('anotherEmail@example.com', $response->json('data.answer.text'));
        Event::assertDispatchedTimes(AnswerCreated::class, 2);
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_cannot_update_an_answer_for_an_email_question_with_random_text()
    {
        $user = factory(User::class)->create();
        $emailQuestion = factory(Question::class)->state('email')->create();
        $user->addFormSteps();
        $answer = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $emailQuestion->id,
            'text' => 'anEmail@example.com',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $emailQuestion->id,
                'text' => 'a non email',
            ])->assertStatus(422);
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_can_create_an_answer_for_age_question()
    {
        $user = factory(User::class)->create();
        $ageQuestion = factory(Question::class)->state('age')->create();
        $user->addFormSteps();

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $ageQuestion->id,
                'text' => '18',
            ])->assertOk();

        $this->assertCount(1, $ageQuestion->currentUserAnswers);
        $this->assertSame(18, $ageQuestion->current_user_answer->text);

        $this->assertSame(18, $response->json('data.answer.text'));
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_can_update_an_answer_for_an_age_question()
    {
        $user = factory(User::class)->create();
        $ageQuestion = factory(Question::class)->state('age')->create();
        $user->addFormSteps();
        $answer = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $ageQuestion->id,
            'text' => '18',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $ageQuestion->id,
                'text' => '20',
            ])->assertOk();

        $this->assertCount(1, $ageQuestion->currentUserAnswers);
        $this->assertSame(20, $ageQuestion->current_user_answer->text);

        $this->assertSame(20, $response->json('data.answer.text'));
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_cannot_update_an_answer_for_an_age_question_with_random_text()
    {
        $user = factory(User::class)->create();
        $ageQuestion = factory(Question::class)->state('age')->create();
        $user->addFormSteps();
        $answer = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $ageQuestion->id,
            'text' => '18',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $ageQuestion->id,
                'text' => '500',
            ])->assertStatus(422);
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_can_create_an_answer_for_phone_question()
    {
        $user = factory(User::class)->create();
        $phoneQuestion = factory(Question::class)->state('phone')->create();
        $user->addFormSteps();

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $phoneQuestion->id,
                'text' => '+55-555-5555555',
            ])->assertOk();

        $this->assertCount(1, $phoneQuestion->currentUserAnswers);
        $this->assertSame('+55-555-5555555', $phoneQuestion->current_user_answer->text);
        $this->assertFalse($phoneQuestion->current_user_answer->confirmed);

        $this->assertSame('+55-555-5555555', $response->json('data.answer.text'));
    }


    /**
     * @test
     * POST '/webforms/answers'
     */
    public function an_event_is_dispatched_when_a_user_create_an_answer_for_phone_question()
    {
        Event::fake(AnswerCreated::class);
        $user = factory(User::class)->create();
        $phoneQuestion = factory(Question::class)->state('phone')->create();
        $user->addFormSteps();

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $phoneQuestion->id,
                'text' => '+55-555-5555555',
            ])->assertOk();

        $this->assertCount(1, $phoneQuestion->currentUserAnswers);
        $this->assertSame('+55-555-5555555', $phoneQuestion->current_user_answer->text);
        $this->assertFalse($phoneQuestion->current_user_answer->confirmed);

        $this->assertSame('+55-555-5555555', $response->json('data.answer.text'));
        Event::assertDispatchedTimes(AnswerCreated::class, 1);
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_can_update_an_answer_for_a_phone_question()
    {
        Event::Fake(AnswerCreated::class);
        $user = factory(User::class)->create();
        $phoneQuestion = factory(Question::class)->state('phone')->create();
        $user->addFormSteps();
        Answer::flushEventListeners();
        $answer = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $phoneQuestion->id,
            'text' => '+55-555-5555555',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $phoneQuestion->id,
                'text' => '+11-111-1111111',
            ])->assertOk();

        $this->assertCount(1, $phoneQuestion->currentUserAnswers);
        $this->assertSame('+11-111-1111111', $phoneQuestion->current_user_answer->text);

        $this->assertSame('+11-111-1111111', $response->json('data.answer.text'));
        Event::assertDispatchedTimes(AnswerCreated::class, 2);
    }

    /**
     * @test
     * POST '/webforms/answers'
     */
    public function a_user_cannot_update_an_answer_for_a_phone_question_with_random_text()
    {
        $user = factory(User::class)->create();
        $ageQuestion = factory(Question::class)->state('phone')->create();
        $user->addFormSteps();
        Answer::flushEventListeners();
        $answer = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $ageQuestion->id,
            'text' => '+11-111-1111111',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms/answers', [
                'question_id' => $ageQuestion->id,
                'text' => '123456789012',
            ])->assertStatus(422);
    }
}
