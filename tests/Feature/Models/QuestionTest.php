<?php

namespace R64\Webforms\Tests\Feature\Models;

use R64\Webforms\Models\Answer;
use R64\Webforms\Models\FormStep;
use R64\Webforms\Models\Question;
use R64\Webforms\Tests\TestCase;

class QuestionTest extends TestCase
{
    /** @test */
    public function it_can_create_models()
    {
        /** @var Question $question */
        $question = factory(Question::class)->create([
            'title' => 'A question?',
            'slug' => 'a-question',
        ]);

        $this->assertDatabaseHas('questions', [
            'title' => 'A question?',
            'slug' => 'a-question',
        ]);
    }

    /** @test */
    public function it_knows_current_user_answers()
    {
        $user = factory(User::class)->create();
        $otherUser = factory(User::class)->create();
        $question = factory(Question::class)->state('text')->create();

        $answersForUser = factory(Answer::class, 2)->state('text')->create([
            'question_id' => $question->id,
            'user_id' => $user->id,
        ]);

        $answersForOtherUser = factory(Answer::class, 4)->state('text')->create([
            'question_id' => $question->id,
            'user_id' => $otherUser->id,
        ]);

        $this->actingAs($user);

        $this->assertCount(2, $question->currentUserAnswers);
        $this->assertCount(2, $question->currentUserAnswers->pluck('id')->intersect($answersForUser->pluck('id')));
    }

    /** @test */
    public function it_knows_current_user_answer()
    {
        $user = factory(User::class)->create();
        $question = factory(Question::class)->state('text')->create();

        factory(Answer::class)->create([
            'question_id' => $question->id,
            'user_id' => $user->id,
            'is_current' => 0,
            'created_at' => '2019-01-01 10:00:00',
        ]);

        factory(Answer::class, 2)->create([
            'question_id' => $question->id,
            'user_id' => $user->id,
            'is_current' => 1,
            'text' => 'this is the latest one',
            'created_at' => '2019-01-01 10:00:01',
        ]);

        $this->actingAs($user);

        $this->assertSame('this is the latest one', $question->current_user_answer->text);
    }

    /** @test */
    public function it_creates_default_answers()
    {
        $user = factory(User::class)->create();
        $formStep = factory(FormStep::class)->create();
        $question = factory(Question::class)->state('text')->create([
            'form_step_id' => $formStep->id,
            'default_value' => '100',
        ]);
        $anotherQuestion = factory(Question::class)->state('text')->create([
            'form_step_id' => $formStep->id,
            'default_value' => '500',
        ]);

        $answerForQuestion = factory(Answer::class)->state('text')->create([
            'user_id' => $user->id,
            'question_id' => $question->id,
            'text' => '700',
        ]);

        $this->assertCount(1, $user->answers);

        $user->addFormSteps();

        $this->assertCount(2, $user->refresh()->answers);
    }
}
