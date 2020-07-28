<?php

namespace R64\Webforms\Tests\Feature\Models;

use R64\Webforms\Models\FormSection;
use R64\Webforms\Models\FormStep;
use R64\Webforms\Tests\TestCase;

class FormSectionTest extends TestCase
{
    /** @test */
    public function it_can_create_models()
    {
        /** @var FormSection $formSection */
        $formSection = factory(FormSection::class)->create([
            'title' => 'First Section',
            'slug' => 'first-section',
        ]);

        $this->assertDatabaseHas('form_sections', [
            'title' => 'First Section',
            'slug' => 'first-section',
        ]);
    }

    /** @test */
    public function menu_title_returns_title_if_it_is_null()
    {
        /** @var FormSection $formSection */
        $formSection = factory(FormSection::class)->create([
            'title' => 'First Section',
            'slug' => 'first-section',
        ]);

        $this->assertEquals('First Section', $formSection->menu_title);
    }

    /** @test */
    public function it_can_add_steps()
    {
        $formSection = factory(FormSection::class)->create();

        $formStep = factory(FormStep::class)->make();

        $formSection->addFormStep($formStep);

        $this->assertCount(1, $formSection->formSteps);
        $this->assertEquals($formSection->id, $formSection->formSteps->first()->id);
    }

    /** @test */
    public function it_can_remove_steps()
    {
        $formSection = factory(FormSection::class)->create();

        $formStep = factory(FormStep::class)->create([
            'form_section_id' => $formSection->id,
        ]);

        $anotherFormStep = factory(FormStep::class)->create([
            'form_section_id' => $formSection->id,
        ]);

        $this->assertCount(2, $formSection->formSteps);
        $this->assertEquals($formStep->id, $formSection->formSteps->sortBy('id')->first()->id);
        $this->assertEquals($anotherFormStep->id, $formSection->formSteps->sortBy('id')->last()->id);

        $formSection->removeFormStep($formStep);

        $formSection->refresh();
        $this->assertCount(1, $formSection->formSteps);
        $this->assertEquals($anotherFormStep->id, $formSection->formSteps->sortBy('id')->first()->id);
    }
}
