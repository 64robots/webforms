<?php

namespace R64\Webforms\Tests\Feature\Http\Controllers;

use R64\Webforms\Models\Section;
use R64\Webforms\Tests\TestCase;

class SectionControllerTest extends TestCase
{
    /** @test */
    public function it_returns_sections()
    {
        factory(Section::class, 2)->create();

        $response = $this->json('GET', '/webforms/sections')->assertOk();

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'name',
                    'slug',
                    'description',
                ],
            ],
        ]);

        $this->assertCount(2, $response->json('data'));
    }
}
