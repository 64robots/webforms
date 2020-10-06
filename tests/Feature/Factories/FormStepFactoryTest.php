<?php

namespace R64\Webforms\Tests\Feature\Factories;

use R64\Webforms\Models\FormSection;
use R64\Webforms\Models\FormStep;
use R64\Webforms\Tests\TestCase;

class FormStepFactoryTest extends TestCase
{
    /** @test */
    public function it_creates_a_form_step_in_a_fluent_way()
    {
        $formSection = FormSection::build('A section')->save();

        FormStep::build($formSection, 'New Step title')
            ->sort(2)
            ->slug('new-step-slug')
            ->menuTitle('new step menu title')
            ->description('new step description')
            ->isPersonalData(1)
            ->save();

        $this->assertDatabaseHas((new FormStep)->getTable(), [
            'form_section_id' => $formSection->id,
            'sort' => 2,
            'slug' => 'new-step-slug',
            'menu_title' => 'new step menu title',
            'title' => 'New Step title',
            'description' => 'new step description',
            'is_personal_data' => 1,
        ]);
    }

    /** @test */
    public function it_creates_a_form_step_only_with_the_name_fluent_way()
    {
        $formSection = FormSection::build('A section')->save();

        FormStep::build($formSection, 'New Step')->save();

        $this->assertDatabaseHas((new FormStep)->getTable(), [
            'slug' => 'new-step',
            'sort' => 1,
            'title' => 'New Step',
        ]);
    }

    /** @test */
    public function it_is_able_to_move_the_sort_values_in_the_form_steps()
    {
        $formSection = FormSection::build('A section')->save();

        $firstStep = FormStep::build($formSection, 'First step')->sort(1)->save();
        $secondStep = FormStep::build($formSection, 'Second step')->sort(2)->save();

        $thirdStep = FormStep::build($formSection, 'Third step')->sort(1)->save();

        $this->assertEquals(1, $thirdStep->fresh()->sort);
        $this->assertEquals(2, $firstStep->fresh()->sort);
        $this->assertEquals(3, $secondStep->fresh()->sort);
    }

    /** @test */
    public function it_can_update_a_existent_form_step()
    {
        $formSection = FormSection::build('A section')->save();
        $firstStep = FormStep::build($formSection, 'First step')->save();

        $firstStep = $firstStep->fresh();
        $anotherFormSection = FormSection::build('Another section')->save();
        FormStep::updateFormStep($firstStep)->formSection($anotherFormSection)->save();
        $firstStep = $firstStep->fresh();
        $this->assertEquals(1, $firstStep->sort);
        $this->assertEquals($anotherFormSection->id, $firstStep->form_section_id);
        $this->assertEquals('First step', $firstStep->title);
        $this->assertEquals('first-step', $firstStep->slug);
        $this->assertNull($firstStep->menu_title);
        $this->assertNull($firstStep->description);
        $this->assertFalse($firstStep->is_personal_data);

        $firstStep = $firstStep->fresh();
        FormStep::updateFormStep($firstStep)->title('Second Step')->save();
        $firstStep = $firstStep->fresh();
        $this->assertEquals(1, $firstStep->sort);
        $this->assertEquals($anotherFormSection->id, $firstStep->form_section_id);
        $this->assertEquals('Second Step', $firstStep->title);
        $this->assertEquals('first-step', $firstStep->slug);
        $this->assertNull($firstStep->menu_title);
        $this->assertNull($firstStep->description);
        $this->assertFalse($firstStep->is_personal_data);

        $firstStep = $firstStep->fresh();
        FormStep::updateFormStep($firstStep)->slug('second-step')->save();
        $firstStep = $firstStep->fresh();
        $this->assertEquals(1, $firstStep->sort);
        $this->assertEquals($anotherFormSection->id, $firstStep->form_section_id);
        $this->assertEquals('Second Step', $firstStep->title);
        $this->assertEquals('second-step', $firstStep->slug);
        $this->assertNull($firstStep->menu_title);
        $this->assertNull($firstStep->description);
        $this->assertFalse($firstStep->is_personal_data);

        $firstStep = $firstStep->fresh();
        FormStep::updateFormStep($firstStep)->menuTitle('Second Step Menu Title')->save();
        $firstStep = $firstStep->fresh();
        $this->assertEquals(1, $firstStep->sort);
        $this->assertEquals($anotherFormSection->id, $firstStep->form_section_id);
        $this->assertEquals('Second Step', $firstStep->title);
        $this->assertEquals('second-step', $firstStep->slug);
        $this->assertEquals('Second Step Menu Title', $firstStep->menu_title);
        $this->assertNull($firstStep->description);
        $this->assertFalse($firstStep->is_personal_data);

        $firstStep = $firstStep->fresh();
        FormStep::updateFormStep($firstStep)->description('Second Step Description')->save();
        $firstStep = $firstStep->fresh();
        $this->assertEquals(1, $firstStep->sort);
        $this->assertEquals($anotherFormSection->id, $firstStep->form_section_id);
        $this->assertEquals('Second Step', $firstStep->title);
        $this->assertEquals('second-step', $firstStep->slug);
        $this->assertEquals('Second Step Menu Title', $firstStep->menu_title);
        $this->assertEquals('Second Step Description', $firstStep->description);
        $this->assertFalse($firstStep->is_personal_data);

        $firstStep = $firstStep->fresh();
        FormStep::updateFormStep($firstStep)->isPersonalData(1)->save();
        $firstStep = $firstStep->fresh();
        $this->assertEquals(1, $firstStep->sort);
        $this->assertEquals($anotherFormSection->id, $firstStep->form_section_id);
        $this->assertEquals('Second Step', $firstStep->title);
        $this->assertEquals('second-step', $firstStep->slug);
        $this->assertEquals('Second Step Menu Title', $firstStep->menu_title);
        $this->assertEquals('Second Step Description', $firstStep->description);
        $this->assertTrue($firstStep->is_personal_data);
    }

    /** @test */
    public function it_is_able_to_update_the_sort_values_for_a_form_step()
    {
        $formSection = FormSection::build('A section')->save();
        $firstStep = FormStep::build($formSection, 'First step')->sort(1)->save();
        $secondStep = FormStep::build($formSection, 'Second step')->sort(2)->save();
        $thirdStep = FormStep::build($formSection, 'Third step')->sort(3)->save();

        FormStep::updateFormStep($thirdStep)->sort(1)->save();

        $this->assertEquals(1, $thirdStep->fresh()->sort);
        $this->assertEquals(2, $firstStep->fresh()->sort);
        $this->assertEquals(3, $secondStep->fresh()->sort);
    }
}
