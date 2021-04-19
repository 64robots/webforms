<?php

namespace R64\Webforms\Tests\Feature\Models;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use R64\Webforms\Models\Answer;
use R64\Webforms\Models\FormStep;
use R64\Webforms\Models\Question;
use R64\Webforms\Tests\TestCase;

class AnswerTest extends TestCase
{
    /** @test */
    public function it_knows_date_type()
    {
        $dateAnswer = factory(Answer::class)->state('date')->create(['text' => '2010-01-01']);

        $this->assertEquals(Carbon::parse('2010-01-01'), $dateAnswer->text);
    }

    /** @test */
    public function it_knows_year_month_type()
    {
        $yearMonthAnswer = factory(Answer::class)->state('year-month')->create(['text' => '2010-01']);

        $this->assertEquals(Carbon::parse('2010-01'), $yearMonthAnswer->text);
    }

    /** @test */
    public function it_knows_integer_type()
    {
        $integerAnswer = factory(Answer::class)->state('integer')->create(['text' => '123']);

        $this->assertSame(123, $integerAnswer->text);
    }

    /** @test */
    public function it_knows_money_type()
    {
        $integerAnswer = factory(Answer::class)->state('money')->create(['text' => '12000']);

        $this->assertSame(12000, $integerAnswer->text);
    }

    /** @test */
    public function it_knows_age_type()
    {
        $ageAnswer = factory(Answer::class)->state('age')->create(['text' => '50']);

        $this->assertSame(50, $ageAnswer->text);
    }

    /** @test */
    public function it_knows_percent_type()
    {
        $integerAnswer = factory(Answer::class)->state('percent')->create(['text' => '95']);

        $this->assertSame(95, $integerAnswer->text);
    }

    /** @test */
    public function it_knows_boolean_type()
    {
        $booleanAnswer = factory(Answer::class)->state('boolean')->create(['text' => '0']);

        $this->assertSame(false, $booleanAnswer->text);

        $booleanAnswer = factory(Answer::class)->state('boolean')->create(['text' => 'false']);

        $this->assertSame(false, $booleanAnswer->text);

        $booleanAnswer = factory(Answer::class)->state('boolean')->create(['text' => '1']);

        $this->assertSame(true, $booleanAnswer->text);

        $booleanAnswer = factory(Answer::class)->state('boolean')->create(['text' => 'true']);

        $this->assertSame(true, $booleanAnswer->text);
    }

    /** @test */
    public function it_knows_options_type()
    {
        $optionsAnswer = factory(Answer::class)->state('options')->create(['text' => 'option 1']);

        $this->assertSame('option 1', $optionsAnswer->text);
    }

    /** @test */
    public function it_knows_text_type()
    {
        $textAnswer = factory(Answer::class)->state('text')->create(['text' => 'Some text']);

        $this->assertSame('Some text', $textAnswer->text);
    }

    /** @test */
    public function it_knows_phone_type()
    {
        $phoneAnswer = factory(Answer::class)->state('text')->create(['text' => '+11-111-1111111']);

        $this->assertSame('+11-111-1111111', $phoneAnswer->text);
    }

    /** @test */
    public function it_knows_email_type()
    {
        $emailAnswer = factory(Answer::class)->state('email')->create(['text' => 'email@example.com']);

        $this->assertSame('email@example.com', $emailAnswer->text);
    }

    /** @test */
    public function it_saves_as_plain_text_not_personal_data_text()
    {
        $dataCollectionOneAnswer = factory(Answer::class)->state('not_personal_data')->create(['text' => 'A text']);

        $this->assertDatabaseHas('answers', [
            'text' => 'A text',
        ]);

        $answerInDatabase = DB::table((new Answer)->getTable())->select('text')->get()->last();
        $this->assertSame('A text', $answerInDatabase->text);


        $this->assertSame('A text', $dataCollectionOneAnswer->text);
    }

    /** @test */
    public function it_encrypts_data_personal_data_text()
    {
        $dataCollectionTwoAnswer = factory(Answer::class)->state('personal_data')->create(['text' => 'Encrypted text']);

        $this->assertDatabaseMissing('answers', [
            'text' => 'Encrypted text',
        ]);

        $answerInDatabase = DB::table((new Answer)->getTable())->select('text')->get()->last();
        $this->assertSame('Encrypted text', decrypt($answerInDatabase->text));

        $this->assertSame('Encrypted text', $dataCollectionTwoAnswer->text);
    }

    /** @test */
    public function it_marks_step_form_as_completed_if_all_question_are_answered()
    {
        $user = factory(User::class)->create();
        $firstFormStep = factory(FormStep::class)->create(['sort' => 1]);
        $secondFormStep = factory(FormStep::class)->create(['sort' => 2]);
        $user->addFormSteps();

        $questionForStepOne = factory(Question::class)->state('text')->create([
            'form_step_id' => $firstFormStep->id,
            'required' => true,
        ]);
        $otherQuestionForStepOne = factory(Question::class)->state('text')->create([
            'form_step_id' => $firstFormStep->id,
            'required' => true,
        ]);
        $otherStepQuestion = factory(Question::class)->state('text')->create([
            'form_step_id' => $secondFormStep->id,
            'required' => true,
        ]);

        $this->actingAs($user);

        $this->assertFalse($firstFormStep->is_completed_by_current_user);

        Answer::makeOneOrUpdate([
            'user_id' => $user->id,
            'question_id' => $questionForStepOne->id,
            'text' => 'A text',
        ]);

        $this->assertFalse($firstFormStep->fresh()->is_completed_by_current_user);

        Answer::makeOneOrUpdate([
            'user_id' => $user->id,
            'question_id' => $otherQuestionForStepOne->id,
            'text' => 'A text',
        ]);

        $this->assertTrue($firstFormStep->fresh()->is_completed_by_current_user);
    }

    /** @test */
    public function it_marks_step_form_as_uncompleted_if_all_question_are_answered_but_one_of_them_is_unreal()
    {
        $user = factory(User::class)->create();
        $firstFormStep = factory(FormStep::class)->create(['sort' => 1]);
        $secondFormStep = factory(FormStep::class)->create(['sort' => 2]);
        $user->addFormSteps();

        $questionForStepOne = factory(Question::class)->state('text')->create([
            'form_step_id' => $firstFormStep->id,
            'required' => true,
        ]);
        $otherQuestionForStepOne = factory(Question::class)->state('text')->create([
            'form_step_id' => $firstFormStep->id,
            'required' => true,
        ]);
        $otherStepQuestion = factory(Question::class)->state('text')->create([
            'form_step_id' => $secondFormStep->id,
            'required' => true,
        ]);

        $this->actingAs($user);

        $this->assertFalse($firstFormStep->is_completed_by_current_user);

        $answer = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $questionForStepOne->id,
            'text' => 'A text',
            'is_real' => false,
        ]);

        $otherQuestionAnswer = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $otherQuestionForStepOne->id,
            'text' => 'A text',
            'is_real' => false,
        ]);

        $this->assertFalse($firstFormStep->fresh()->is_completed_by_current_user);

        Answer::makeOneOrUpdate([
            'user_id' => $user->id,
            'question_id' => $questionForStepOne->id,
            'text' => 'A text',
        ], $answer);

        $this->assertFalse($firstFormStep->fresh()->is_completed_by_current_user);

        Answer::makeOneOrUpdate([
            'user_id' => $user->id,
            'question_id' => $otherQuestionForStepOne->id,
            'text' => 'A text',
        ], $otherQuestionAnswer);

        $this->assertTrue($firstFormStep->fresh()->is_completed_by_current_user);
    }

    /** @test */
    public function it_marks_step_form_step_as_uncompleted_if_all_question_are_answered_but_one_of_them_is_deleted()
    {
        $user = factory(User::class)->create();
        $firstFormStep = factory(FormStep::class)->create(['sort' => 1]);
        $secondFormStep = factory(FormStep::class)->create(['sort' => 2]);
        $user->addFormSteps();

        $questionForStepOne = factory(Question::class)->state('text')->create([
            'form_step_id' => $firstFormStep->id,
            'required' => true,
        ]);
        $otherQuestionForStepOne = factory(Question::class)->state('text')->create([
            'form_step_id' => $firstFormStep->id,
            'required' => true,
        ]);
        $otherStepQuestion = factory(Question::class)->state('text')->create([
            'form_step_id' => $secondFormStep->id,
            'required' => true,
        ]);

        $this->actingAs($user);

        $this->assertFalse($firstFormStep->is_completed_by_current_user);

        Answer::makeOneOrUpdate([
            'user_id' => $user->id,
            'question_id' => $questionForStepOne->id,
            'text' => 'A text',
        ]);

        $this->assertFalse($firstFormStep->fresh()->is_completed_by_current_user);

        $answer = Answer::makeOneOrUpdate([
            'user_id' => $user->id,
            'question_id' => $otherQuestionForStepOne->id,
            'text' => 'A text',
        ]);

        $this->assertTrue($firstFormStep->fresh()->is_completed_by_current_user);

        $answer->deleteMe();

        $this->assertFalse($firstFormStep->fresh()->is_completed_by_current_user);
    }

    /** @test */
    public function it_marks_step_form_as_completed_if_all_required_question_are_answered()
    {
        $user = factory(User::class)->create();
        $firstFormStep = factory(FormStep::class)->create(['sort' => 1]);
        $secondFormStep = factory(FormStep::class)->create(['sort' => 2]);
        $user->addFormSteps();

        $questionForStepOne = factory(Question::class)->state('text')->create([
            'form_step_id' => $firstFormStep->id,
            'required' => true,
        ]);
        $otherQuestionForStepOne = factory(Question::class)->state('text')->create([
            'form_step_id' => $firstFormStep->id,
            'required' => false,
        ]);
        $otherStepQuestion = factory(Question::class)->state('text')->create([
            'form_step_id' => $secondFormStep->id,
            'required' => true,
        ]);

        $this->actingAs($user);

        $this->assertFalse($firstFormStep->is_completed_by_current_user);

        Answer::makeOneOrUpdate([
            'user_id' => $user->id,
            'question_id' => $questionForStepOne->id,
            'text' => 'A text',
        ]);

        $this->assertTrue($firstFormStep->fresh()->is_completed_by_current_user);
    }

    /** @test */
    public function an_answer_can_be_deleted()
    {
        $user = factory(User::class)->create();
        $answer = factory(Answer::class)->state('text')->create([
            'user_id' => $user->id,
        ]);

        $this->assertCount(1, $user->answers);

        $this->actingAs($user);

        $answer->deleteMe();

        $this->assertCount(0, $user->fresh()->answers);
    }

    /** @test */
    public function current_scope_is_working()
    {
        $answer = factory(Answer::class)->state('text')->create([
            'is_current' => 0,
        ]);
        $answer = factory(Answer::class)->state('text')->create([
            'is_current' => 1,
        ]);
        $this->assertCount(1, Answer::current()->get());
        $this->assertCount(2, Answer::all());
    }

    /** @test */
    public function form_step_with_mother_integer_question_is_marked_as_completed_when_required_son_questions_are_answered()
    {
        $formStep = factory(FormStep::class)->create();
        $user = factory(User::class)->create();
        $user->addFormSteps();
        $motherQuestion = factory(Question::class)->state('integer')->create([
            'form_step_id' => $formStep->id,
            'required' => true,
        ]);
        $sonQuestion = factory(Question::class)->state('date')->create([
            'form_step_id' => $formStep->id,
            'required' => false,
            'depends_on' => $motherQuestion->id,
            'shown_when' => [1,2,3],
        ]);
        $anotherSonQuestion = factory(Question::class)->state('date')->create([
            'form_step_id' => $formStep->id,
            'required' => false,
            'depends_on' => $motherQuestion->id,
            'shown_when' => [2,3],
        ]);
        $thirdSonQuestionNotAnswerNeeded = factory(Question::class)->state('date')->create([
            'form_step_id' => $formStep->id,
            'required' => false,
            'depends_on' => $motherQuestion->id,
            'shown_when' => [3],
        ]);

        $this->assertFalse($user->formSteps()->find($formStep)->pivot->completed);

        $this->actingAs($user);

        Answer::makeOneOrUpdate([
            'user_id' => $user->id,
            'question_id' => $motherQuestion->id,
            'text' => '2',
        ]);

        $this->assertFalse($user->formSteps()->find($formStep)->pivot->completed);

        Answer::makeOneOrUpdate([
            'user_id' => $user->id,
            'question_id' => $sonQuestion->id,
            'text' => '2010-05-21',
        ]);

        $this->assertFalse($user->formSteps()->find($formStep)->pivot->completed);

        Answer::makeOneOrUpdate([
            'user_id' => $user->id,
            'question_id' => $anotherSonQuestion->id,
            'text' => '2010-05-21',
        ]);

        $this->assertTrue($user->formSteps()->find($formStep)->pivot->completed);
    }

    /** @test */
    public function form_step_with_mother_boolean_question_is_marked_as_completed_when_required_son_questions_are_answered()
    {
        $formStep = factory(FormStep::class)->create();
        $user = factory(User::class)->create();
        $user->addFormSteps();
        $motherQuestion = factory(Question::class)->state('boolean')->create([
            'form_step_id' => $formStep->id,
            'required' => true,
        ]);
        $sonQuestion = factory(Question::class)->state('date')->create([
            'form_step_id' => $formStep->id,
            'required' => false,
            'depends_on' => $motherQuestion->id,
            'shown_when' => [true],
        ]);
        $anotherSonQuestion = factory(Question::class)->state('date')->create([
            'form_step_id' => $formStep->id,
            'required' => false,
            'depends_on' => $motherQuestion->id,
            'shown_when' => [true],
        ]);
        $thirdSonQuestionNotAnswerNeeded = factory(Question::class)->state('date')->create([
            'form_step_id' => $formStep->id,
            'required' => false,
            'depends_on' => $motherQuestion->id,
            'shown_when' => [false],
        ]);

        $this->assertFalse($user->formSteps()->find($formStep)->pivot->completed);

        $this->actingAs($user);

        Answer::makeOneOrUpdate([
            'user_id' => $user->id,
            'question_id' => $motherQuestion->id,
            'text' => '2',
        ]);

        $this->assertFalse($user->formSteps()->find($formStep)->pivot->completed);

        Answer::makeOneOrUpdate([
            'user_id' => $user->id,
            'question_id' => $sonQuestion->id,
            'text' => '2010-05-21',
        ]);

        $this->assertFalse($user->formSteps()->find($formStep)->pivot->completed);

        Answer::makeOneOrUpdate([
            'user_id' => $user->id,
            'question_id' => $anotherSonQuestion->id,
            'text' => '2010-05-21',
        ]);

        $this->assertTrue($user->formSteps()->find($formStep)->pivot->completed);
    }

    /** @test */
    public function form_step_with_mother_boolean_question_is_marked_as_completed_with_fictional_answers_when_required_son_questions_are_really_answered()
    {
        $formStep = factory(FormStep::class)->create();
        $user = factory(User::class)->create();
        $user->addFormSteps();
        $motherQuestion = factory(Question::class)->state('boolean')->create([
            'form_step_id' => $formStep->id,
            'required' => true,
        ]);
        $sonQuestion = factory(Question::class)->state('date')->create([
            'form_step_id' => $formStep->id,
            'required' => false,
            'depends_on' => $motherQuestion->id,
            'shown_when' => [true],
        ]);
        $anotherSonQuestion = factory(Question::class)->state('date')->create([
            'form_step_id' => $formStep->id,
            'required' => false,
            'depends_on' => $motherQuestion->id,
            'shown_when' => [true],
        ]);
        $thirdSonQuestionNotAnswerNeeded = factory(Question::class)->state('date')->create([
            'form_step_id' => $formStep->id,
            'required' => false,
            'depends_on' => $motherQuestion->id,
            'shown_when' => [false],
        ]);

        $this->assertFalse($user->formSteps()->find($formStep)->pivot->completed);

        $this->actingAs($user);

        Answer::makeOneOrUpdate([
            'user_id' => $user->id,
            'question_id' => $motherQuestion->id,
            'text' => '2',
        ]);

        $this->assertFalse($user->formSteps()->find($formStep)->pivot->completed);

        $answerForSonQuestion = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $sonQuestion->id,
            'text' => '2010-05-20',
            'is_real' => false,
        ]);

        $answerForAnotherSonQuestion = factory(Answer::class)->create([
            'user_id' => $user->id,
            'question_id' => $anotherSonQuestion->id,
            'text' => '2010-05-20',
            'is_real' => false,
        ]);

        Answer::makeOneOrUpdate([
            'user_id' => $user->id,
            'question_id' => $sonQuestion->id,
            'text' => '2010-05-21',
        ], $answerForSonQuestion);

        $this->assertFalse($user->formSteps()->find($formStep)->pivot->completed);

        Answer::makeOneOrUpdate([
            'user_id' => $user->id,
            'question_id' => $anotherSonQuestion->id,
            'text' => '2010-05-21',
        ], $answerForAnotherSonQuestion);

        $this->assertTrue($user->formSteps()->find($formStep)->pivot->completed);
    }

    /** @test */
    public function form_step_with_two_questions_is_mark_as_completed_when_all_required_answers_are_provided()
    {
        $formStep = factory(FormStep::class)->create();
        $user = factory(User::class)->create();
        $user->addFormSteps();
        $motherQuestion = factory(Question::class)->state('integer')->create([
            'form_step_id' => $formStep->id,
            'required' => true,
        ]);
        $questionWithoutChildren = factory(Question::class)->state('integer')->create([
            'form_step_id' => $formStep->id,
            'required' => true,
        ]);
        $sonQuestion = factory(Question::class)->state('date')->create([
            'form_step_id' => $formStep->id,
            'required' => false,
            'depends_on' => $motherQuestion->id,
            'shown_when' => [1,2],
        ]);
        $anotherSonQuestion = factory(Question::class)->state('date')->create([
            'form_step_id' => $formStep->id,
            'required' => false,
            'depends_on' => $motherQuestion->id,
            'shown_when' => [2],
        ]);

        $this->assertFalse($user->formSteps()->find($formStep)->pivot->completed);

        $this->actingAs($user);

        Answer::makeOneOrUpdate([
            'user_id' => $user->id,
            'question_id' => $motherQuestion->id,
            'text' => '2',
        ]);

        $this->assertFalse($user->formSteps()->find($formStep)->pivot->completed);

        Answer::makeOneOrUpdate([
            'user_id' => $user->id,
            'question_id' => $sonQuestion->id,
            'text' => '2010-05-21',
        ]);

        $this->assertFalse($user->formSteps()->find($formStep)->pivot->completed);

        Answer::makeOneOrUpdate([
            'user_id' => $user->id,
            'question_id' => $anotherSonQuestion->id,
            'text' => '2010-05-21',
        ]);

        $this->assertFalse($user->formSteps()->find($formStep)->pivot->completed);

        Answer::makeOneOrUpdate([
            'user_id' => $user->id,
            'question_id' => $questionWithoutChildren->id,
            'text' => '10',
        ]);

        $this->assertTrue($user->formSteps()->find($formStep)->pivot->completed);
    }

    /** @test */
    public function form_step_with_two_questions_is_mark_as_completed_when_all_required_answers_are_provided_starting_without_son_question()
    {
        $formStep = factory(FormStep::class)->create();
        $user = factory(User::class)->create();
        $user->addFormSteps();
        $questionWithoutChildren = factory(Question::class)->state('integer')->create([
            'form_step_id' => $formStep->id,
            'required' => true,
        ]);
        $motherQuestion = factory(Question::class)->state('integer')->create([
            'form_step_id' => $formStep->id,
            'required' => true,
        ]);
        $sonQuestion = factory(Question::class)->state('date')->create([
            'form_step_id' => $formStep->id,
            'required' => false,
            'depends_on' => $motherQuestion->id,
            'shown_when' => [1,2],
        ]);
        $anotherSonQuestion = factory(Question::class)->state('date')->create([
            'form_step_id' => $formStep->id,
            'required' => false,
            'depends_on' => $motherQuestion->id,
            'shown_when' => [2],
        ]);

        $this->assertFalse($user->formSteps()->find($formStep)->pivot->completed);

        $this->actingAs($user);

        Answer::makeOneOrUpdate([
            'user_id' => $user->id,
            'question_id' => $questionWithoutChildren->id,
            'text' => '10',
        ]);

        $this->assertFalse($user->formSteps()->find($formStep)->pivot->completed);

        Answer::makeOneOrUpdate([
            'user_id' => $user->id,
            'question_id' => $motherQuestion->id,
            'text' => '2',
        ]);

        $this->assertFalse($user->formSteps()->find($formStep)->pivot->completed);

        Answer::makeOneOrUpdate([
            'user_id' => $user->id,
            'question_id' => $sonQuestion->id,
            'text' => '2010-05-21',
        ]);

        $this->assertFalse($user->formSteps()->find($formStep)->pivot->completed);

        Answer::makeOneOrUpdate([
            'user_id' => $user->id,
            'question_id' => $anotherSonQuestion->id,
            'text' => '2010-05-21',
        ]);

        $this->assertTrue($user->formSteps()->find($formStep)->pivot->completed);
    }

    /** @test */
    public function it_can_be_marked_as_real()
    {
        $answer = factory(Answer::class)->state('text')->create([
            'is_real' => false,
        ]);

        $this->assertFalse($answer->is_real);

        $answer->markAsReal();

        $this->assertTrue($answer->fresh()->is_real);
    }
}
