<?php

namespace R64\Webforms\Tests\Feature\Http\Controllers;

use R64\Webforms\Models\Form;
use R64\Webforms\Tests\Feature\Models\User;
use R64\Webforms\Tests\TestCase;

class AdminFormDeleteControllerTest extends TestCase
{
    /**
     * @test
     * DELETE '/webforms-admin/forms/{form}'
     */
    public function it_deletes_a_form()
    {
        $user = factory(User::class)->create();
        $form = factory(Form::class)->create([
            'sort' => 1,
            'slug' => 'new-form',
            'menu_title' => 'New form title for the menu',
            'title' => 'New form title',
            'description' => 'An awesome new form',
        ]);

        $response = $this->actingAs($user)
            ->json('DELETE', '/webforms-admin/forms/' . $form->id)
            ->assertStatus(200);

        $this->assertTrue($form->fresh()->trashed());

        $response->assertJsonStructure([
            'data' => [
                'id',
                'sort',
                'slug',
                'menu_title',
                'title',
                'description',
                'completed',
            ],
        ]);

        $this->assertEquals($form->id, $response->json('data.id'));
        $this->assertEquals(1, $response->json('data.sort'));
        $this->assertEquals('new-form', $response->json('data.slug'));
        $this->assertEquals('New form title for the menu', $response->json('data.menu_title'));
        $this->assertEquals('New form title', $response->json('data.title'));
        $this->assertEquals('An awesome new form', $response->json('data.description'));
        $this->assertEquals(true, $response->json('data.completed'));
    }
}
