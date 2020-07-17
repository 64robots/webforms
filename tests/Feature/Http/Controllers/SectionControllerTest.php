<?php

namespace R64\Webforms\Tests\Feature\Http\Controllers;

use R64\Webforms\Models\Section;
use R64\Webforms\Tests\TestCase;

class SectionControllerTest extends TestCase
{
    /** @test */
    public function it_returns_sections()
    {
        $secondSection = factory(Section::class)->create([
            'sort' => 2,
        ]);
        $firstSection = factory(Section::class)->create([
            'sort' => 1,
        ]);

        $response = $this->json('GET', '/webforms/sections')->assertOk();

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
        $this->assertEquals($firstSection->id, $response->json('data.0.id'));
        $this->assertEquals($secondSection->id, $response->json('data.1.id'));
    }
}
