<?php

namespace R64\Webforms\Tests\Feature\Http\Controllers;

use R64\Webforms\Models\Section;
use R64\Webforms\Models\Step;
use R64\Webforms\Tests\TestCase;

class StepControllerTest extends TestCase
{
    /** @test */
    public function it_returns_steps()
    {
        $this->withoutExceptionHandling();
        $section = factory(Section::class)->create();
        $secondStep = factory(Step::class)->create([
            'sort' => 2,
            'section_id' => $section->id,
        ]);
        $firstStep = factory(Step::class)->create([
            'sort' => 1,
            'section_id' => $section->id,
        ]);

        $response = $this->json('GET', '/webforms/steps')->assertOk();

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'section',
                    'sort',
                    'slug',
                    'menu_title',
                    'title',
                    'description',
                ],
            ],
        ]);

        $this->assertCount(2, $response->json('data'));
        $this->assertEquals($firstStep->id, $response->json('data.0.id'));
        $this->assertEquals($secondStep->id, $response->json('data.1.id'));
    }
}
