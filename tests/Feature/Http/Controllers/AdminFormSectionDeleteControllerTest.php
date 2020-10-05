<?php

namespace R64\Webforms\Tests\Feature\Http\Controllers;

use R64\Webforms\Models\FormSection;
use R64\Webforms\Tests\Feature\Models\User;
use R64\Webforms\Tests\TestCase;

class AdminFormSectionDeleteControllerTest extends TestCase
{
    /**
     * @test
     * DELETE '/webforms-admin/form-sections/{formSection}'
     */
    public function it_deletes_a_section()
    {
        $user = factory(User::class)->create();
        $formSection = factory(FormSection::class)->create([
            'sort' => 1,
            'slug' => 'new-form-section',
            'menu_title' => 'New form section title for the menu',
            'title' => 'New form section title',
            'description' => 'An awesome new form section',
        ]);

        $response = $this->actingAs($user)
            ->json('DELETE', '/webforms-admin/form-sections/' . $formSection->id)
            ->assertStatus(200);

        $this->assertTrue($formSection->fresh()->trashed());

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

        $this->assertEquals($formSection->id, $response->json('data.id'));
        $this->assertEquals(1, $response->json('data.sort'));
        $this->assertEquals('new-form-section', $response->json('data.slug'));
        $this->assertEquals('New form section title for the menu', $response->json('data.menu_title'));
        $this->assertEquals('New form section title', $response->json('data.title'));
        $this->assertEquals('An awesome new form section', $response->json('data.description'));
        $this->assertEquals(true, $response->json('data.completed'));
    }
}
