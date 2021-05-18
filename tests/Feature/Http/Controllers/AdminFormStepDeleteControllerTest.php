<?php

namespace R64\Webforms\Tests\Feature\Http\Controllers;

use R64\Webforms\Models\Form;
use R64\Webforms\Models\FormStep;
use R64\Webforms\Tests\Feature\Models\User;
use R64\Webforms\Tests\TestCase;

class AdminFormStepDeleteControllerTest extends TestCase
{
    const FORM_STEP_STRUCTURE = [
        'id',
        'sort',
        'slug',
        'menu_title',
        'title',
        'description',
        'completed',
        'form' => [
            'id',
            'sort',
            'slug',
            'menu_title',
            'title',
            'description',
            'completed',
        ],
    ];

    /**
     * @test
     * DELETE '/webforms-admin/form-steps/{formStep}'
     */
    public function it_deletes_a_step()
    {
        $user = factory(User::class)->create();
        $form = factory(Form::class)->create();
        $formStep = factory(FormStep::class)->create([
            'form_id' => $form->id,
            'sort' => 1,
            'slug' => 'new-form-step',
            'menu_title' => 'New form step title for the menu',
            'title' => 'New form step title',
            'description' => 'An awesome new form step',
            'is_personal_data' => 1,
        ]);

        $response = $this->actingAs($user)
            ->json('DELETE', '/webforms-admin/form-steps/' . $formStep->id)
            ->assertStatus(200);

        $this->assertTrue($formStep->fresh()->trashed());

        $response->assertJsonStructure([
            'data' => self::FORM_STEP_STRUCTURE,
        ]);

        $this->assertEquals($formStep->id, $response->json('data.id'));
        $this->assertEquals(1, $response->json('data.sort'));
        $this->assertEquals('new-form-step', $response->json('data.slug'));
        $this->assertEquals('New form step title for the menu', $response->json('data.menu_title'));
        $this->assertEquals('New form step title', $response->json('data.title'));
        $this->assertEquals('An awesome new form step', $response->json('data.description'));
        $this->assertEquals(1, $formStep->fresh()->is_personal_data);
    }
}
