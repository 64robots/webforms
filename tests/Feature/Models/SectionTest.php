<?php

namespace R64\Webforms\Tests\Feature\Models;

use R64\Webforms\Models\Section;
use R64\Webforms\Models\Step;
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

    /** @test */
    public function it_can_add_steps()
    {
        $section = factory(Section::class)->create();

        $step = factory(Step::class)->make();

        $section->addStep($step);

        $this->assertCount(1, $section->steps);
        $this->assertEquals($section->id, $section->steps->first()->id);
    }

    /** @test */
    public function it_can_remove_steps()
    {
        $section = factory(Section::class)->create();

        $step = factory(Step::class)->create([
            'section_id' => $section->id,
        ]);

        $anotherStep = factory(Step::class)->create([
            'section_id' => $section->id,
        ]);

        $this->assertCount(2, $section->steps);
        $this->assertEquals($section->id, $section->steps->sortBy('id')->first()->id);
        $this->assertEquals($anotherStep->id, $section->steps->sortBy('id')->last()->id);

        $section->removeStep($step);

        $section->refresh();
        $this->assertCount(1, $section->steps);
        $this->assertEquals($anotherStep->id, $section->steps->sortBy('id')->first()->id);
    }
}
