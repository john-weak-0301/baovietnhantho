<?php

namespace Tests\Unit\Model\Helpers;

use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasOptionsAttributeTest extends TestCase
{
    use RefreshDatabase;

    /** @var TestOptionsAttributeModel */
    protected $testModel;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();

        $this->testModel = TestOptionsAttributeModel::create();
    }

    /** @test */
    public function getting_a_non_existing_schemaless_attribute_returns_null()
    {
        $this->assertNull($this->testModel->options->non_existing);
    }

    /** @test */
    public function default_value_can_be_passed_when_getting_a_non_existing_schemaless_attribute()
    {
        $this->assertEquals('default', $this->testModel->options->get('non_existing', 'default'));
    }

    /** @test */
    public function an_schemaless_attribute_can_be_set()
    {
        $this->testModel->options->name = 'value';

        $this->assertEquals('value', $this->testModel->options->name);
    }

    /** @test */
    public function it_can_determine_if_it_has_a_schemaless_attribute()
    {
        $this->assertFalse($this->testModel->options->has('name'));

        $this->testModel->options->name = 'value';

        $this->assertTrue($this->testModel->options->has('name'));
    }

    /** @test */
    public function options_will_get_saved_with_the_model()
    {
        $this->testModel->options->name = 'value';

        $this->testModel->save();

        $this->assertEquals('value', $this->testModel->options->name);
    }

    /** @test */
    public function it_can_handle_an_array()
    {
        $array = [
            'one' => 'value',
            'two' => 'another value',
        ];

        $this->testModel->options->array = $array;

        $this->assertEquals($array, $this->testModel->options->array);
    }

    /** @test */
    public function it_can_get_values_using_dot_notation()
    {
        $this->testModel->options->rey   = ['side' => 'light'];
        $this->testModel->options->snoke = ['side' => 'dark'];

        $this->assertEquals('light', $this->testModel->options->get('rey.side'));
    }

    /** @test */
    public function it_can_set_values_using_dot_notation()
    {
        $this->testModel->options->rey = ['side' => 'light'];
        $this->testModel->options->set('rey.side', 'dark');

        $this->assertEquals('dark', $this->testModel->options->get('rey.side'));
    }

    /** @test */
    public function it_can_get_values_using_wildcards_notation()
    {
        $this->testModel->options->rey = [
            'sides' => [
                ['name' => 'light'],
                ['name' => 'neutral'],
                ['name' => 'dark'],
            ],
        ];

        $this->assertEquals(['light', 'neutral', 'dark'],
            $this->testModel->options->get('rey.sides.*.name'));
    }

    /** @test */
    public function it_can_set_values_using_wildcard_notation()
    {
        $this->testModel->options->rey = [
            'sides' => [
                ['name' => 'light'],
                ['name' => 'neutral'],
                ['name' => 'dark'],
            ],
        ];

        $this->testModel->options->set('rey.sides.*.name', 'dark');

        $this->assertEquals(['dark', 'dark', 'dark'], $this->testModel->options->get('rey.sides.*.name'));
    }

    /** @test */
    public function it_can_set_all_options_at_once()
    {
        $array = [
            'rey'   => ['side' => 'light'],
            'snoke' => ['side' => 'dark'],
        ];

        $this->testModel->options = $array;

        $this->assertEquals($array, $this->testModel->options->all());
    }

    /** @test */
    public function it_can_forget_a_single_schemaless_attribute()
    {
        $this->testModel->options->name = 'value';

        $this->assertEquals('value', $this->testModel->options->name);

        $this->testModel->options->forget('name');

        $this->assertNull($this->testModel->options->name);
    }

    /** @test */
    public function it_can_forget_a_schemaless_attribute_using_dot_notation()
    {
        $this->testModel->options->member = ['name' => 'John', 'age' => 30];

        $this->testModel->options->forget('member.age');

        $this->assertEquals($this->testModel->options->member, ['name' => 'John']);
    }

    /** @test */
    public function it_can_get_all_options()
    {
        $this->testModel->options = ['name' => 'value'];

        $this->assertEquals(['name' => 'value'], $this->testModel->options->all());
    }

    /** @test */
    public function it_will_use_the_correct_datatype()
    {
        $this->testModel->options->bool  = true;
        $this->testModel->options->float = 12.34;

        $this->testModel->save();

        $this->testModel->refresh();

        $this->assertSame(true, $this->testModel->options->bool);
        $this->assertSame(12.34, $this->testModel->options->float);
    }

    /** @test */
    public function it_can_be_handled_as_an_array()
    {
        $this->testModel->options['name'] = 'value';

        $this->assertEquals('value', $this->testModel->options['name']);

        $this->assertTrue(isset($this->testModel->options['name']));

        unset($this->testModel->options['name']);

        $this->assertFalse(isset($this->testModel->options['name']));

        $this->assertNull($this->testModel->options['name']);
    }

    /** @test */
    public function it_can_be_counted()
    {
        $this->assertCount(0, $this->testModel->options);

        $this->testModel->options->name = 'value';

        $this->assertCount(1, $this->testModel->options);
    }

    /** @test */
    public function it_can_be_used_as_an_arrayable()
    {
        $this->testModel->options->name = 'value';

        $this->assertEquals(
            $this->testModel->options->toArray(),
            $this->testModel->options->all()
        );
    }

    /** @test */
    public function it_can_add_and_save_options_in_one_go()
    {
        $array = [
            'name'  => 'value',
            'name2' => 'value2',
        ];

        $testModel = TestOptionsAttributeModel::create(['options' => $array]);

        $this->assertEquals($array, $testModel->options->all());
    }

    /** @test */
    public function it_has_a_scope_to_get_models_with_the_given_options()
    {
        TestOptionsAttributeModel::truncate();

        $model1 = TestOptionsAttributeModel::create([
            'options' => [
                'name'  => 'value',
                'name2' => 'value2',
            ],
        ]);

        $model2 = TestOptionsAttributeModel::create([
            'options' => [
                'name'  => 'value',
                'name2' => 'value2',
            ],
        ]);

        $model3 = TestOptionsAttributeModel::create([
            'options' => [
                'name'  => 'value',
                'name2' => 'value3',
            ],
        ]);

        $this->assertContainsModels([
            $model1, $model2,
        ], TestOptionsAttributeModel::withOptions(['name' => 'value', 'name2' => 'value2'])->get());

        $this->assertContainsModels([
            $model3,
        ], TestOptionsAttributeModel::withOptions(['name' => 'value', 'name2' => 'value3'])->get());

        $this->assertContainsModels([
        ], TestOptionsAttributeModel::withOptions(['name' => 'value', 'non-existing' => 'value'])->get());

        $this->assertContainsModels([
            $model1, $model2, $model3,
        ], TestOptionsAttributeModel::withOptions([])->get());

        $this->assertContainsModels([
            $model1, $model2, $model3,
        ], TestOptionsAttributeModel::withOptions('name', 'value')->get());

        $this->assertContainsModels([
        ], TestOptionsAttributeModel::withOptions('name', 'non-existing-value')->get());

        $this->assertContainsModels([
        ], TestOptionsAttributeModel::withOptions('name', 'non-existing-value')->get());
    }

    /** @test */
    public function it_can_set_multiple_attributes_one_after_the_other()
    {
        $this->testModel->options->name  = 'value';
        $this->testModel->options->name2 = 'value2';

        $this->assertEquals([
            'name'  => 'value',
            'name2' => 'value2',
        ], $this->testModel->options->all());
    }

    /** @test */
    public function it_returns_an_array_that_can_be_looped()
    {
        $this->testModel->options->name  = 'value';
        $this->testModel->options->name2 = 'value2';

        $attributes = $this->testModel->options->all();

        $this->assertCount(2, $attributes);

        foreach ($attributes as $key => $value) {
            $this->assertNotNull($key);
            $this->assertNotNull($value);
        }
    }

    /** @test */
    public function it_can_multiple_attributes_at_once_by_passing_an_array_argument()
    {
        $this->testModel->options->set([
            'foo' => 'bar',
            'baz' => 'buzz',
            'arr' => [
                'subKey1' => 'subVal1',
                'subKey2' => 'subVal2',
            ],
        ]);

        $this->assertEquals('bar', $this->testModel->options->foo);
        $this->assertCount(2, $this->testModel->options->arr);
        $this->assertEquals('subVal1', $this->testModel->options->arr['subKey1']);
    }

    /** @test */
    public function if_an_iterable_is_passed_to_set_it_will_defer_to_setMany()
    {
        $this->testModel->options->set([
            'foo' => 'bar',
            'baz' => 'buzz',
            'arr' => [
                'subKey1' => 'subVal1',
                'subKey2' => 'subVal2',
            ],
        ]);

        $this->assertEquals('bar', $this->testModel->options->foo);
        $this->assertCount(2, $this->testModel->options->arr);
        $this->assertEquals('subVal1', $this->testModel->options->arr['subKey1']);
    }

    protected function assertContainsModels(array $expectedModels, $actualModels)
    {
        $assertionFailedMessage = 'Expected '.count($expectedModels).' models. Got '.$actualModels->count().' models';

        $this->assertEquals(count($expectedModels), $actualModels->count(), $assertionFailedMessage);
    }

    protected function setUpDatabase(): void
    {
        Schema::dropIfExists('test_options_models');

        Schema::create('test_options_models', function (Blueprint $table) {
            $table->increments('id');
            $table->jsonb('options')->nullable();
        });
    }
}
