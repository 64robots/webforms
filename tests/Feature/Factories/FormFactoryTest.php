<?php

namespace R64\Webforms\Tests\Feature\Factories;

use R64\Webforms\Models\Form;
use R64\Webforms\Tests\TestCase;

class FormFactoryTest extends TestCase
{
    /** @test */
    public function it_creates_a_form_in_a_fluent_way()
    {
        Form::build('New Form title')
            ->sort(2)
            ->slug('new-form-slug')
            ->menuTitle('new form menu title')
            ->description('new form description')
            ->save();

        $this->assertDatabaseHas((new Form)->getTable(), [
            'sort' => 2,
            'slug' => 'new-form-slug',
            'menu_title' => 'new form menu title',
            'title' => 'New Form title',
            'description' => 'new form description',
        ]);
    }

    /** @test */
    public function it_creates_a_form_only_with_the_name_fluent_way()
    {
        Form::build('New Form')->save();

        $this->assertDatabaseHas((new Form)->getTable(), [
            'slug' => 'new-form',
            'sort' => 1,
            'title' => 'New Form',
        ]);
    }

    /** @test */
    public function it_is_able_to_move_the_sort_values_in_the_forms()
    {
        $firstForm = Form::build('First form')->sort(1)->save();
        $secondForm = Form::build('Second form')->sort(2)->save();

        $thirdForm = Form::build('Third form')->sort(1)->save();

        $this->assertEquals(1, $thirdForm->fresh()->sort);
        $this->assertEquals(2, $firstForm->fresh()->sort);
        $this->assertEquals(3, $secondForm->fresh()->sort);
    }

    /** @test */
    public function it_can_update_a_existent_form()
    {
        $firstForm = Form::build('First form')->save();

        $firstForm = $firstForm->fresh();
        Form::updateForm($firstForm)->title('Second Form')->save();
        $firstForm = $firstForm->fresh();
        $this->assertEquals(1, $firstForm->sort);
        $this->assertEquals('Second Form', $firstForm->title);
        $this->assertEquals('first-form', $firstForm->slug);
        $this->assertNull($firstForm->menu_title);
        $this->assertNull($firstForm->description);

        $firstForm = $firstForm->fresh();
        Form::updateForm($firstForm)->slug('second-form')->save();
        $firstForm = $firstForm->fresh();
        $this->assertEquals(1, $firstForm->sort);
        $this->assertEquals('Second Form', $firstForm->title);
        $this->assertEquals('second-form', $firstForm->slug);
        $this->assertNull($firstForm->menu_title);
        $this->assertNull($firstForm->description);

        $firstForm = $firstForm->fresh();
        Form::updateForm($firstForm)->menuTitle('Second Form Menu Title')->save();
        $firstForm = $firstForm->fresh();
        $this->assertEquals(1, $firstForm->sort);
        $this->assertEquals('Second Form', $firstForm->title);
        $this->assertEquals('second-form', $firstForm->slug);
        $this->assertEquals('Second Form Menu Title', $firstForm->menu_title);
        $this->assertNull($firstForm->description);

        $firstForm = $firstForm->fresh();
        Form::updateForm($firstForm)->description('Second Form Description')->save();
        $firstForm = $firstForm->fresh();
        $this->assertEquals(1, $firstForm->sort);
        $this->assertEquals('Second Form', $firstForm->title);
        $this->assertEquals('second-form', $firstForm->slug);
        $this->assertEquals('Second Form Menu Title', $firstForm->menu_title);
        $this->assertEquals('Second Form Description', $firstForm->description);
    }

    /** @test */
    public function it_is_able_to_update_the_sort_values_for_a_form()
    {
        $firstForm = Form::build('First form')->sort(1)->save();
        $secondForm = Form::build('Second form')->sort(2)->save();
        $thirdForm = Form::build('Third form')->sort(3)->save();

        Form::updateForm($thirdForm)->sort(1)->save();

        $this->assertEquals(1, $thirdForm->fresh()->sort);
        $this->assertEquals(2, $firstForm->fresh()->sort);
        $this->assertEquals(3, $secondForm->fresh()->sort);
    }
}
