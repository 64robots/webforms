<?php

namespace R64\Webforms\Tests\Feature\Models;

use R64\Webforms\Models\Section;
use R64\Webforms\Models\Step;
use R64\Webforms\Tests\TestCase;

class StepTest extends TestCase
{
    /** @test */
    public function it_can_create_models()
    {
        /** @var Section $section */
        $section = factory(Section::class)->create();

        /** @var Step $step */
        $step = factory(Step::class)->create([
            'section_id' => $section->id,
            'name' => 'First Step',
            'slug' => 'first-step',
            'sort' => 1,
        ]);

        $this->assertDatabaseHas('steps', [
            'name' => 'First Step',
            'slug' => 'first-step',
            'sort' => 1,
        ]);
    }

    /** @test */
    public function it_belongs_to_a_section()
    {
        /** @var Section $section */
        $section = factory(Section::class)->create();
        /** @var Section $anotherSection */
        $anotherSection = factory(Section::class)->create();
        /** @var Step $step */
        $step = factory(Step::class)->make();

        $step->associateSection($section);

        $this->assertEquals($section->id, $step->section_id);

        $step->associateSection($anotherSection);

        $this->assertEquals($anotherSection->id, $step->refresh()->section_id);
    }
}
