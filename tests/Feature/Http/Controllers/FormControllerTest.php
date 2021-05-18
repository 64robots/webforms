<?php

namespace R64\Webforms\Tests\Feature\Http\Controllers;

use R64\Webforms\Models\Form;
use R64\Webforms\Tests\Feature\Models\User;
use R64\Webforms\Tests\TestCase;

class FormControllerTest extends TestCase
{
    /**
     * @test
     * GET '/webforms/forms'
     */
    public function it_returns_forms()
    {
        $user = factory(User::class)->create();
        $secondForm = factory(Form::class)->create([
            'sort' => 2,
        ]);
        $firstForm = factory(Form::class)->create([
            'sort' => 1,
        ]);

        $response = $this->actingAs($user)
            ->json('GET', '/webforms/forms')
            ->assertOk();

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
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
        $this->assertEquals($firstForm->id, $response->json('data.0.id'));
        $this->assertEquals($secondForm->id, $response->json('data.1.id'));
    }
}
