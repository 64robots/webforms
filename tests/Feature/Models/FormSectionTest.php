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
    public function it_can_be_associated_with_a_entity_using_form_sectionable()
    {
        $user = factory(User::class)->create();
        $formSection = factory(FormSection::class)->create();
        $user->associateFormSection($formSection);
        $this->assertTrue($user->formSection->is($formSection));
        $this->assertTrue($formSection->formSectionable->is($user));
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

    /** @test */
    public function it_knows_about_if_current_user_had_completed_it()
    {
        $user = factory(User::class)->create();
        $formSection = factory(FormSection::class)->create();
        $formStep = factory(FormStep::class)->create([
            'form_section_id' => $formSection->id,
        ]);
        $anotherFormStep = factory(FormStep::class)->create([
            'form_section_id' => $formSection->id,
        ]);
        $user->addFormSteps();
        $user->markFormStepAsCompleted($formStep);

        $this->actingAs($user);
        $this->assertFalse($formSection->refresh()->is_completed_by_current_user);

        $user->markFormStepAsCompleted($anotherFormStep);

        $this->assertTrue($formSection->refresh()->is_completed_by_current_user);
    }
}
