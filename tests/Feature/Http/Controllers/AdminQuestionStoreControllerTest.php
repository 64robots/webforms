<?php

namespace R64\Webforms\Tests\Feature\Http\Controllers;

use R64\Webforms\Models\FormStep;
use R64\Webforms\Models\Question;
use R64\Webforms\Tests\Feature\Models\User;
use R64\Webforms\Tests\TestCase;

class AdminQuestionStoreControllerTest extends TestCase
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
        'error_message',
        'default_value',
        'min',
        'max',
        'options',
        'answer',
    ];

    /**
     * @test
     * POST '/webforms-admin/question'
     */
    public function it_creates_a_question()
    {
        $user = factory(User::class)->create();
        $formStep = factory(FormStep::class)->create();
        $anotherQuestion = factory(Question::class)->create();

        $response = $this->actingAs($user)
            ->json('POST', '/webforms-admin/questions', [
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
                'showed_when' => [true],
                'options' => [
                    '5' => 'Minimum value',
                    '10' => 'Average value',
                    '15' => 'Maximum value',
                ],
                'required' => 1,
            ])
            ->assertStatus(201);

        $response->assertJsonStructure([
            'data' => self::QUESTION_STRUCTURE,
        ]);

        $question = Question::where('slug', 'new-question')->first();
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
        $this->assertEquals('New question title', $response->json('data.title'));
        $this->assertEquals('An awesome new question', $response->json('data.description'));
        $this->assertEquals('Sorry, we need that value', $response->json('data.error_message'));
        $this->assertEquals('10', $response->json('data.default_value'));
        $this->assertEquals('5', $response->json('data.min'));
        $this->assertEquals('15', $response->json('data.max'));
        $this->assertEquals([true], $response->json('data.showed_when'));
        $this->assertEquals([
            ['value' => 5, 'label' => 'Minimum value'],
            ['value' => 10, 'label' => 'Average value'],
            ['value' => 15, 'label' => 'Maximum value'],
        ], $response->json('data.options'));
        $this->assertEquals(1, $response->json('data.required'));
    }

    /**
     * @test
     * POST '/webforms-admin/questions'
     */
    public function it_validates_uniqueness_of_the_slug_when_it_creates_a_new_question()
    {
        $user = factory(User::class)->create();
        $formStep = factory(FormStep::class)->create();

        factory(Question::class)->create([
            'slug' => 'new-question-title',
        ]);

        $this->actingAs($user)
            ->json('POST', '/webforms-admin/questions', [
                'form_step_id' => $formStep->id,
                'slug' => 'new-question-title',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('slug');
    }

    /**
     * @test
     * POST '/webforms-admin/questions'
     */
    public function it_creates_a_slug_for_a_question()
    {
        $user = factory(User::class)->create();
        $formStep = factory(FormStep::class)->create();

        factory(Question::class)->create([
            'form_step_id' => $formStep->id,
            'slug' => 'new-question-title',
        ]);

        factory(Question::class)->create([
            'form_step_id' => $formStep->id,
            'slug' => 'new-question-title-1',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms-admin/questions', [
                'form_step_id' => $formStep->id,
                'sort' => 3,
                'title' => 'New question title',
            ])
            ->assertStatus(201);

        $response->assertJsonStructure([
            'data' => self::QUESTION_STRUCTURE,
        ]);

        $question = Question::where('slug', 'new-question-title-2')->first();
        $this->assertEquals($question->id, $response->json('data.id'));
        $this->assertEquals('new-question-title-2', $response->json('data.slug'));
        $this->assertEquals('text', $response->json('data.type'));
        $this->assertEquals('top', $response->json('data.label_position'));
        $this->assertEquals(false, $response->json('data.required'));
    }

    /**
     * @test
     * POST '/webforms-admin/questions'
     */
    public function it_creates_a_sort_for_a_question()
    {
        $user = factory(User::class)->create();
        $formStep = factory(FormStep::class)->create();

        factory(Question::class)->create([
            'form_step_id' => $formStep->id,
            'sort' => 1,
            'slug' => 'new-question-title',
        ]);

        factory(Question::class)->create([
            'form_step_id' => $formStep->id,
            'sort' => 2,
            'slug' => 'new-question-title-1',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms-admin/questions', [
                'form_step_id' => $formStep->id,
                'slug' => 'new-question',
                'title' => 'New question title',
            ])
            ->assertStatus(201);

        $response->assertJsonStructure([
            'data' => self::QUESTION_STRUCTURE,
        ]);

        $question = Question::where('slug', 'new-question')->first();
        $this->assertEquals($question->id, $response->json('data.id'));
        $this->assertEquals(3, $response->json('data.sort'));
    }

    /**
     * @test
     * POST '/webforms-admin/questions'
     */
    public function sort_works_fine()
    {
        $user = factory(User::class)->create();
        $formStep = factory(FormStep::class)->create();

        $secondStep = factory(Question::class)->create([
            'form_step_id' => $formStep->id,
            'sort' => 1,
            'slug' => 'new-question-title',
        ]);

        $thirdStep = factory(Question::class)->create([
            'form_step_id' => $formStep->id,
            'sort' => 2,
            'slug' => 'new-question-title-1',
        ]);

        $response = $this->actingAs($user)
            ->json('POST', '/webforms-admin/questions', [
                'form_step_id' => $formStep->id,
                'sort' => 1,
                'slug' => 'new-question',
                'title' => 'New question title',
            ])
            ->assertStatus(201);

        $response->assertJsonStructure([
            'data' => self::QUESTION_STRUCTURE,
        ]);

        $question = Question::where('slug', 'new-question')->first();
        $this->assertEquals($question->id, $response->json('data.id'));
        $this->assertEquals(1, $response->json('data.sort'));
        $this->assertEquals(2, $secondStep->fresh()->sort);
        $this->assertEquals(3, $thirdStep->fresh()->sort);
    }
}
