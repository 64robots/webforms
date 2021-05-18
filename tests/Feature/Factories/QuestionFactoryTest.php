<?php

namespace R64\Webforms\Tests\Feature\Factories;

use R64\Webforms\Models\Form;
use R64\Webforms\Models\FormStep;
use R64\Webforms\Models\Question;
use R64\Webforms\Tests\TestCase;

class QuestionFactoryTest extends TestCase
{
    /** @test */
    public function it_creates_a_question_in_a_fluent_way()
    {
        $form = Form::build('A form')->save();
        $formStep = FormStep::build($form, 'A form step')->save();
        $parentQuestion = Question::build($formStep, 'Parent Question')->save();

        $question = Question::build($formStep, 'New question title')
            ->sort(2)
            ->slug('new-question-slug')
            ->dependsOn($parentQuestion)
            ->description('new question description')
            ->groupBy('A group by text')
            ->groupByDescription('A description for a group by text')
            ->labelPosition('left')
            ->helpTitle('A help title')
            ->helpBody('A help body')
            ->postInputText('A post input text')
            ->errorMessage('A custom error message')
            ->defaultValue('A default value')
            ->min(10)
            ->max(30)
            ->shownWhen([10, 30])
            ->options([10, 20, 30])
            ->required(1)
            ->save();

        $this->assertDatabaseHas((new Question)->getTable(), [
            'form_step_id' => $formStep->id,
            'sort' => 2,
            'slug' => 'new-question-slug',
            'title' => 'New question title',
            'description' => 'new question description',
            'group_by' => 'A group by text',
            'group_by_description' => 'A description for a group by text',
            'label_position' => 'left',
            'help_title' => 'A help title',
            'help_body' => 'A help body',
            'post_input_text' => 'A post input text',
            'error_message' => 'A custom error message',
            'default_value' => 'A default value',
            'type' => 'text',
            'min' => '10',
            'max' => '30',
            'required' => 1,
            'depends_on' => $parentQuestion->id,
        ]);

        $this->assertEquals([10, 30], $question->shown_when);
        $this->assertEquals([10, 20, 30], $question->options);
    }

    /** @test */
    public function it_creates_a_question_only_with_the_name_in_a_fluent_way()
    {
        $form = Form::build('A form')->save();
        $formStep = FormStep::build($form, 'A form step')->save();

        Question::build($formStep, 'New Question')->save();

        $this->assertDatabaseHas((new Question)->getTable(), [
            'slug' => 'new-question',
            'sort' => 1,
            'title' => 'New Question',
        ]);
    }

    /** @test */
    public function it_is_able_to_move_the_sort_values_in_the_questions()
    {
        $form = Form::build('A form')->save();
        $formStep = FormStep::build($form, 'A form step')->save();

        $firstQuestion = Question::build($formStep, 'First question')->sort(1)->save();
        $secondQuestion = Question::build($formStep, 'Second question')->sort(2)->save();

        $thirdQuestion = Question::build($formStep, 'Third question')->sort(1)->save();

        $this->assertEquals(1, $thirdQuestion->fresh()->sort);
        $this->assertEquals(2, $firstQuestion->fresh()->sort);
        $this->assertEquals(3, $secondQuestion->fresh()->sort);
    }

    /** @test */
    public function it_can_update_only_the_title_of_a_question_in_a_fluent_way()
    {
        $form = Form::build('A form')->save();
        $formStep = FormStep::build($form, 'A form step')->save();
        $parentQuestion = Question::build($formStep, 'Parent Question')->save();

        $question = Question::build($formStep, 'New question title')
            ->sort(2)
            ->slug('new-question-slug')
            ->dependsOn($parentQuestion)
            ->description('new question description')
            ->groupBy('A group by text')
            ->groupByDescription('A description for a group by text')
            ->labelPosition('left')
            ->helpTitle('A help title')
            ->helpBody('A help body')
            ->postInputText('A post input text')
            ->errorMessage('A custom error message')
            ->defaultValue('A default value')
            ->min(10)
            ->max(30)
            ->shownWhen([10, 30])
            ->options([10, 20, 30])
            ->required(1)
            ->save();

        Question::updateQuestion($question)->title('Edited new title question')->save();

        $this->assertDatabaseHas((new Question)->getTable(), [
            'form_step_id' => $formStep->id,
            'sort' => 2,
            'slug' => 'new-question-slug',
            'title' => 'Edited new title question',
            'description' => 'new question description',
            'group_by' => 'A group by text',
            'group_by_description' => 'A description for a group by text',
            'label_position' => 'left',
            'help_title' => 'A help title',
            'help_body' => 'A help body',
            'post_input_text' => 'A post input text',
            'error_message' => 'A custom error message',
            'default_value' => 'A default value',
            'type' => 'text',
            'min' => '10',
            'max' => '30',
            'required' => 1,
            'depends_on' => $parentQuestion->id,
        ]);

        $this->assertEquals([10, 30], $question->shown_when);
        $this->assertEquals([10, 20, 30], $question->options);
    }

    /** @test */
    public function it_can_update_a_existent_form_step()
    {
        $form = Form::build('A form')->save();
        $formStep = FormStep::build($form, 'A form step')->save();
        $question = Question::build($formStep, 'A question')->save();

        $question = $question->fresh();
        $anotherFormStep = FormStep::build($form, 'Another form step')->save();
        Question::updateQuestion($question)->formStep($anotherFormStep)->save();
        $question = $question->fresh();
        $this->assertEquals(1, $question->sort);
        $this->assertEquals($anotherFormStep->id, $question->formStep->id);

        $question = $question->fresh();
        Question::updateQuestion($question)->title('Second Question')->save();
        $question = $question->fresh();
        $this->assertEquals(1, $question->sort);
        $this->assertEquals('Second Question', $question->title);

        $question = $question->fresh();
        Question::updateQuestion($question)->slug('second-question')->save();
        $question = $question->fresh();
        $this->assertEquals(1, $question->sort);
        $this->assertEquals('second-question', $question->slug);

        $question = $question->fresh();
        Question::updateQuestion($question)->description('Second Question Description')->save();
        $question = $question->fresh();
        $this->assertEquals(1, $question->sort);
        $this->assertEquals('Second Question Description', $question->description);

        $question = $question->fresh();
        Question::updateQuestion($question)->groupBy('Second Question Group By')->save();
        $question = $question->fresh();
        $this->assertEquals(1, $question->sort);
        $this->assertEquals('Second Question Group By', $question->group_by);

        $question = $question->fresh();
        Question::updateQuestion($question)->groupByDescription('Second Question Group By Description')->save();
        $question = $question->fresh();
        $this->assertEquals(1, $question->sort);
        $this->assertEquals('Second Question Group By Description', $question->group_by_description);

        $question = $question->fresh();
        Question::updateQuestion($question)->labelPosition('left')->save();
        $question = $question->fresh();
        $this->assertEquals(1, $question->sort);
        $this->assertEquals('left', $question->label_position);

        $question = $question->fresh();
        Question::updateQuestion($question)->helpTitle('Second Question Help Title')->save();
        $question = $question->fresh();
        $this->assertEquals(1, $question->sort);
        $this->assertEquals('Second Question Help Title', $question->help_title);

        $question = $question->fresh();
        Question::updateQuestion($question)->helpBody('Second Question Help Body')->save();
        $question = $question->fresh();
        $this->assertEquals(1, $question->sort);
        $this->assertEquals('Second Question Help Body', $question->help_body);

        $question = $question->fresh();
        Question::updateQuestion($question)->postInputText('Second Question Post Input Text')->save();
        $question = $question->fresh();
        $this->assertEquals(1, $question->sort);
        $this->assertEquals('Second Question Post Input Text', $question->post_input_text);

        $question = $question->fresh();
        Question::updateQuestion($question)->errorMessage('Second Question Error Message')->save();
        $question = $question->fresh();
        $this->assertEquals(1, $question->sort);
        $this->assertEquals('Second Question Error Message', $question->error_message);

        $question = $question->fresh();
        $parentQuestion = Question::build($formStep, 'A parent question')->save();
        Question::updateQuestion($question)->dependsOn($parentQuestion)->save();
        $question = $question->fresh();
        $this->assertEquals(1, $question->sort);
        $this->assertEquals($parentQuestion->id, $question->depends_on);

        $question = $question->fresh();
        Question::updateQuestion($question)->defaultValue('10')->save();
        $question = $question->fresh();
        $this->assertEquals(1, $question->sort);
        $this->assertEquals('10', $question->default_value);

        $question = $question->fresh();
        Question::updateQuestion($question)->min('50')->save();
        $question = $question->fresh();
        $this->assertEquals(1, $question->sort);
        $this->assertEquals('50', $question->min);

        $question = $question->fresh();
        Question::updateQuestion($question)->max('100')->save();
        $question = $question->fresh();
        $this->assertEquals(1, $question->sort);
        $this->assertEquals('100', $question->max);

        $question = $question->fresh();
        Question::updateQuestion($question)->shownWhen([1, 2, 3])->save();
        $question = $question->fresh();
        $this->assertEquals(1, $question->sort);
        $this->assertEquals([1, 2, 3], $question->shown_when);

        $question = $question->fresh();
        Question::updateQuestion($question)->options([10, 20, 30])->save();
        $question = $question->fresh();
        $this->assertEquals(1, $question->sort);
        $this->assertEquals([10, 20, 30], $question->options);

        $question = $question->fresh();
        Question::updateQuestion($question)->required(1)->save();
        $question = $question->fresh();
        $this->assertEquals(1, $question->sort);
        $this->assertEquals(1, $question->required);
    }

    /** @test */
    public function it_is_able_to_update_the_sort_values_for_a_question()
    {
        $form = Form::build('A form')->save();
        $formStep = FormStep::build($form, 'First step')->save();

        $firstQuestion = Question::build($formStep, 'First question')->sort(1)->save();
        $secondQuestion = Question::build($formStep, 'Second question')->sort(2)->save();
        $thirdQuestion = Question::build($formStep, 'Third question')->sort(3)->save();

        Question::updateQuestion($thirdQuestion)->sort(1)->save();

        $this->assertEquals(1, $thirdQuestion->fresh()->sort);
        $this->assertEquals(2, $firstQuestion->fresh()->sort);
        $this->assertEquals(3, $secondQuestion->fresh()->sort);
    }

    /** @test */
    public function it_is_able_to_update_the_sort_values_for_a_question_to_move_to_the_last_position()
    {
        $form = Form::build('A form')->save();
        $formStep = FormStep::build($form, 'First step')->save();

        $firstQuestion = Question::build($formStep, 'First question')->sort(1)->save();
        $secondQuestion = Question::build($formStep, 'Second question')->sort(2)->save();
        $thirdQuestion = Question::build($formStep, 'Third question')->sort(3)->save();

        Question::updateQuestion($firstQuestion)->sort(3)->save();

        $this->assertEquals(2, $secondQuestion->fresh()->sort);
        $this->assertEquals(3, $firstQuestion->fresh()->sort);
        $this->assertEquals(4, $thirdQuestion->fresh()->sort);
    }
}
