<?php

namespace R64\Webforms\Tests\Feature\Models;

use R64\Webforms\Models\Section;
use R64\Webforms\Tests\TestCase;

class SectionTest extends TestCase
{
    /** @test */
    public function it_can_create_models()
    {
        /** @var Section $section */
        $section = factory(Section::class)->create([
            'name' => 'First Section',
            'slug' => 'first-section',
        ]);

        $this->assertDatabaseHas('sections', [
            'name' => 'First Section',
            'slug' => 'first-section',
        ]);
    }
}
