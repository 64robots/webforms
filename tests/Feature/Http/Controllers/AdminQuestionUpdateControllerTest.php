<?php

namespace R64\Webforms\Tests\Feature\Http\Controllers;

use R64\Webforms\Models\FormStep;
use R64\Webforms\Models\Question;
use R64\Webforms\Tests\Feature\Models\User;
use R64\Webforms\Tests\TestCase;

class AdminQuestionUpdateControllerTest extends TestCase
{
    const QUESTION_STRUCTURE = [
        'id',
        'form_step' => [
            'id',
            'sort',
            'slug',
            'menu_title',
            'title',
            'description',
            'completed',
        ],
        'sort',
        'depends_on',
        'shown_when',
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
        'error_message',
        'default_value',
        'min',
        'max',
        'options',
        'answer',
    ];

    /**
     * @test
     * PUT '/webforms-admin/question/{question}'
     */
    public function it_udpates_a_question()
    {
        $user = factory(User::class)->create();
        $formStep = factory(FormStep::class)->create();
        $anotherQuestion = factory(Question::class)->create();
        $newQuestionToDependsOn = factory(Question::class)->create();
        $question = factory(Question::class)->create([
            'form_step_id' => $formStep->id,
            'depends_on' => $anotherQuestion->id,
            'sort' => 2,
            'slug' => 'new-question',
            'group_by' => 'Group for questions in the same form step',
            'group_by_description' => 'Group description for questions in the same form step',
            'label_position' => 'right',
            'help_title' => 'A little help usually as a modal',
            'help_body' => 'Body of a little help usually as a modal',
            'type' => 'text',
            'post_input_text' => 'Answer something about that',
            'title' => 'New question title',
            'description' => 'An awesome new question',
            'error_message' => 'Sorry, we need that value',
            'default_value' => '10',
            'min' => '5',
            'max' => '15',
            'shown_when' => null,
            'options' => null,
            'required' => 0,
        ]);

        $response = $this->actingAs($user)
            ->json('PUT', '/webforms-admin/questions/' . $question->id, [
                'form_step_id' => $formStep->id,
                'depends_on' => $newQuestionToDependsOn->id,
                'sort' => 1,
                'slug' => 'edited-new-question',
                'group_by' => 'Edited group for questions in the same form step',
                'group_by_description' => 'Edited group description for questions in the same form step',
                'label_position' => 'left',
                'help_title' => 'Edited a little help usually as a modal',
                'help_body' => 'Edited body of a little help usually as a modal',
                'type' => 'options',
                'post_input_text' => 'Edited answer something about that',
                'title' => 'Edited new question title',
                'description' => 'Edited an awesome new question',
                'error_message' => 'Edited sorry, we need that value',
                'default_value' => '20',
                'min' => '10',
                'max' => '30',
                'shown_when' => [true],
                'options' => [
                    '10' => 'Minimum value',
                    '20' => 'Average value',
                    '30' => 'Maximum value',
                ],
                'required' => 1,
            ])
            ->assertStatus(200);

        $response->assertJsonStructure([
            'data' => self::QUESTION_STRUCTURE,
        ]);

        $this->assertEquals($question->id, $response->json('data.id'));
        $this->assertEquals($formStep->id, $response->json('data.form_step.id'));
        $this->assertEquals($newQuestionToDependsOn->id, $response->json('data.depends_on'));
        $this->assertEquals(1, $response->json('data.sort'));
        $this->assertEquals('edited-new-question', $response->json('data.slug'));
        $this->assertEquals('Edited group for questions in the same form step', $response->json('data.group_by'));
        $this->assertEquals('Edited group description for questions in the same form step', $response->json('data.group_by_description'));
        $this->assertEquals('left', $response->json('data.label_position'));
        $this->assertEquals('Edited a little help usually as a modal', $response->json('data.help_title'));
        $this->assertEquals('Edited body of a little help usually as a modal', $response->json('data.help_body'));
        $this->assertEquals('options', $response->json('data.type'));
        $this->assertEquals('Edited answer something about that', $response->json('data.post_input_text'));
        $this->assertEquals('Edited new question title', $response->json('data.title'));
        $this->assertEquals('Edited an awesome new question', $response->json('data.description'));
        $this->assertEquals('Edited sorry, we need that value', $response->json('data.error_message'));
        $this->assertEquals('20', $response->json('data.default_value'));
        $this->assertEquals('10', $response->json('data.min'));
        $this->assertEquals('30', $response->json('data.max'));
        $this->assertEquals([true], $response->json('data.shown_when'));
        $this->assertEquals([
            ['value' => 10, 'label' => 'Minimum value'],
            ['value' => 20, 'label' => 'Average value'],
            ['value' => 30, 'label' => 'Maximum value'],
        ], $response->json('data.options'));
        $this->assertEquals(1, $response->json('data.required'));
    }

    /**
     * @test
     * PUT '/webforms-admin/question/{question}'
     */
    public function it_can_update_only_the_title_of_a_question()
    {
        $user = factory(User::class)->create();
        $formStep = factory(FormStep::class)->create();
        $anotherQuestion = factory(Question::class)->create();
        $question = factory(Question::class)->create([
            'form_step_id' => $formStep->id,
            'depends_on' => $anotherQuestion->id,
            'sort' => 2,
            'slug' => 'new-question',
            'group_by' => 'Group for questions in the same form step',
            'group_by_description' => 'Group description for questions in the same form step',
            'label_position' => 'right',
            'help_title' => 'A little help usually as a modal',
            'help_body' => 'Body of a little help usually as a modal',
            'type' => 'options',
            'post_input_text' => 'Answer something about that',
            'title' => 'New question title',
            'description' => 'An awesome new question',
            'error_message' => 'Sorry, we need that value',
            'default_value' => '10',
            'min' => '5',
            'max' => '15',
            'shown_when' => [true],
            'options' => [
                '5' => 'Minimum value',
                '10' => 'Average value',
                '15' => 'Maximum value',
            ],
            'required' => 1,
        ]);

        $response = $this->actingAs($user)
            ->json('PUT', '/webforms-admin/questions/' . $question->id, [
                'title' => 'Edited new question title',
            ])
            ->assertStatus(200);

        $response->assertJsonStructure([
            'data' => self::QUESTION_STRUCTURE,
        ]);

        $this->assertEquals($question->id, $response->json('data.id'));
        $this->assertEquals($formStep->id, $response->json('data.form_step.id'));
        $this->assertEquals($anotherQuestion->id, $response->json('data.depends_on'));
        $this->assertEquals(2, $response->json('data.sort'));
        $this->assertEquals('new-question', $response->json('data.slug'));
        $this->assertEquals('Group for questions in the same form step', $response->json('data.group_by'));
        $this->assertEquals('Group description for questions in the same form step', $response->json('data.group_by_description'));
        $this->assertEquals('right', $response->json('data.label_position'));
        $this->assertEquals('A little help usually as a modal', $response->json('data.help_title'));
        $this->assertEquals('Body of a little help usually as a modal', $response->json('data.help_body'));
        $this->assertEquals('options', $response->json('data.type'));
        $this->assertEquals('Answer something about that', $response->json('data.post_input_text'));
        $this->assertEquals('Edited new question title', $response->json('data.title'));
        $this->assertEquals('An awesome new question', $response->json('data.description'));
        $this->assertEquals('Sorry, we need that value', $response->json('data.error_message'));
        $this->assertEquals('10', $response->json('data.default_value'));
        $this->assertEquals('5', $response->json('data.min'));
        $this->assertEquals('15', $response->json('data.max'));
        $this->assertEquals([true], $response->json('data.shown_when'));
        $this->assertEquals([
            ['value' => 5, 'label' => 'Minimum value'],
            ['value' => 10, 'label' => 'Average value'],
            ['value' => 15, 'label' => 'Maximum value'],
        ], $response->json('data.options'));
        $this->assertEquals(1, $response->json('data.required'));
    }

    /**
     * @test
     * PUT '/webforms-admin/questions/{question}'
     */
    public function it_validates_uniqueness_of_the_slug_when_it_updates_a_new_question()
    {
        $user = factory(User::class)->create();

        factory(Question::class)->create([
            'slug' => 'new-question-title',
        ]);

        $question = factory(Question::class)->create([
            'slug' => 'another-question-title',
        ]);

        $this->actingAs($user)
            ->json('PUT', '/webforms-admin/questions/' . $question->id, [
                'slug' => 'new-question-title',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('slug');
    }

    /**
     * @test
     * PUT '/webforms-admin/questions/{question}'
     */
    public function it_updates_sort_for_a_question()
    {
        $user = factory(User::class)->create();
        $formStep = factory(FormStep::class)->create();

        $firstQuestion = factory(Question::class)->create([
            'form_step_id' => $formStep->id,
            'sort' => 1,
            'slug' => 'first-question-title',
        ]);

        $secondQuestion = factory(Question::class)->create([
            'form_step_id' => $formStep->id,
            'sort' => 2,
            'slug' => 'second-question-title',
        ]);

        $thirdQuestion = factory(Question::class)->create([
            'form_step_id' => $formStep->id,
            'sort' => 3,
            'slug' => 'third-question-title',
        ]);

        $response = $this->actingAs($user)
            ->json('PUT', '/webforms-admin/questions/' . $thirdQuestion->id, [
                'sort' => 1,
            ])
            ->assertStatus(200);

        $response->assertJsonStructure([
            'data' => self::QUESTION_STRUCTURE,
        ]);

        $this->assertEquals($thirdQuestion->id, $response->json('data.id'));
        $this->assertEquals(1, $response->json('data.sort'));

        $this->assertEquals(1, $thirdQuestion->fresh()->sort);
        $this->assertEquals(2, $firstQuestion->fresh()->sort);
        $this->assertEquals(3, $secondQuestion->fresh()->sort);
    }
}
