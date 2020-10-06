<?php

namespace R64\Webforms\Tests\Feature\Factories;

use R64\Webforms\Models\FormSection;
use R64\Webforms\Tests\TestCase;

class FormSectionFactoryTest extends TestCase
{
    /** @test */
    public function it_creates_a_form_section_in_a_fluent_way()
    {
        FormSection::build('New Section title')
            ->sort(2)
            ->slug('new-section-slug')
            ->menuTitle('new section menu title')
            ->description('new section description')
            ->save();

        $this->assertDatabaseHas((new FormSection)->getTable(), [
            'sort' => 2,
            'slug' => 'new-section-slug',
            'menu_title' => 'new section menu title',
            'title' => 'New Section title',
            'description' => 'new section description',
        ]);
    }

    /** @test */
    public function it_creates_a_form_section_only_with_the_name_fluent_way()
    {
        FormSection::build('New Section')->save();

        $this->assertDatabaseHas((new FormSection)->getTable(), [
            'slug' => 'new-section',
            'sort' => 1,
            'title' => 'New Section',
        ]);
    }

    /** @test */
    public function it_is_able_to_move_the_sort_values_in_the_form_sections()
    {
        $firstSection = FormSection::build('First section')->sort(1)->save();
        $secondSection = FormSection::build('Second section')->sort(2)->save();

        $thirdSection = FormSection::build('Third section')->sort(1)->save();

        $this->assertEquals(1, $thirdSection->fresh()->sort);
        $this->assertEquals(2, $firstSection->fresh()->sort);
        $this->assertEquals(3, $secondSection->fresh()->sort);
    }

    /** @test */
    public function it_can_update_a_existent_form_section()
    {
        $firstSection = FormSection::build('First section')->save();

        $firstSection = $firstSection->fresh();
        FormSection::updateFormSection($firstSection)->title('Second Section')->save();
        $firstSection = $firstSection->fresh();
        $this->assertEquals(1, $firstSection->sort);
        $this->assertEquals('Second Section', $firstSection->title);
        $this->assertEquals('first-section', $firstSection->slug);
        $this->assertNull($firstSection->menu_title);
        $this->assertNull($firstSection->description);

        $firstSection = $firstSection->fresh();
        FormSection::updateFormSection($firstSection)->slug('second-section')->save();
        $firstSection = $firstSection->fresh();
        $this->assertEquals(1, $firstSection->sort);
        $this->assertEquals('Second Section', $firstSection->title);
        $this->assertEquals('second-section', $firstSection->slug);
        $this->assertNull($firstSection->menu_title);
        $this->assertNull($firstSection->description);

        $firstSection = $firstSection->fresh();
        FormSection::updateFormSection($firstSection)->menuTitle('Second Section Menu Title')->save();
        $firstSection = $firstSection->fresh();
        $this->assertEquals(1, $firstSection->sort);
        $this->assertEquals('Second Section', $firstSection->title);
        $this->assertEquals('second-section', $firstSection->slug);
        $this->assertEquals('Second Section Menu Title', $firstSection->menu_title);
        $this->assertNull($firstSection->description);

        $firstSection = $firstSection->fresh();
        FormSection::updateFormSection($firstSection)->description('Second Section Description')->save();
        $firstSection = $firstSection->fresh();
        $this->assertEquals(1, $firstSection->sort);
        $this->assertEquals('Second Section', $firstSection->title);
        $this->assertEquals('second-section', $firstSection->slug);
        $this->assertEquals('Second Section Menu Title', $firstSection->menu_title);
        $this->assertEquals('Second Section Description', $firstSection->description);
    }

    /** @test */
    public function it_is_able_to_update_the_sort_values_for_a_form_section()
    {
        $firstSection = FormSection::build('First section')->sort(1)->save();
        $secondSection = FormSection::build('Second section')->sort(2)->save();
        $thirdSection = FormSection::build('Third section')->sort(3)->save();

        FormSection::updateFormSection($thirdSection)->sort(1)->save();

        $this->assertEquals(1, $thirdSection->fresh()->sort);
        $this->assertEquals(2, $firstSection->fresh()->sort);
        $this->assertEquals(3, $secondSection->fresh()->sort);
    }
}
