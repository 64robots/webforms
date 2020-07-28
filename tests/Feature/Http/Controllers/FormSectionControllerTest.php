<?php

namespace R64\Webforms\Tests\Feature\Http\Controllers;

use R64\Webforms\Models\FormSection;
use R64\Webforms\Tests\TestCase;

class FormSectionControllerTest extends TestCase
{
    /**
     * @test
     * GET '/webforms/form-sections'
     */
    public function it_returns_sections()
    {
        $secondFormSection = factory(FormSection::class)->create([
            'sort' => 2,
        ]);
        $firstFormSection = factory(FormSection::class)->create([
            'sort' => 1,
        ]);

        $response = $this->json('GET', '/webforms/form-sections')->assertOk();

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'sort',
                    'slug',
                    'menu_title',
                    'title',
                    'description',
                ],
            ],
        ]);

        $this->assertCount(2, $response->json('data'));
        $this->assertEquals($firstFormSection->id, $response->json('data.0.id'));
        $this->assertEquals($secondFormSection->id, $response->json('data.1.id'));
    }
}
