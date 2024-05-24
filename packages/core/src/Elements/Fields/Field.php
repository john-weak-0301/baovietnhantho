<?php

namespace Core\Elements\Fields;

use Closure;
use Core\Elements\Element;
use Orchid\Screen\Contracts\FieldContract;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\ViewErrorBag;

class Field extends Element implements FieldContract, Renderable
{
    use OrchidFieldCompat,
        ResolveOldValue;

    /* Constants */
    public const LAYOUT_VERTICAL = 'platform::partials.fields.vertical';
    public const LAYOUT_HORIZONTAL = 'platform::partials.fields.horizontal';

    /**
     * The displayable name of the field.
     *
     * @var string
     */
    public $name;

    /**
     * The attribute/column name of the field.
     *
     * @var string
     */
    public $attribute;

    /**
     * The field's resolved value.
     *
     * @var mixed
     */
    public $value;

    /**
     * Indicates if the field is nullable.
     *
     * @var bool
     */
    public $nullable = false;

    /**
     * Values which will be replaced to null.
     *
     * @var array
     */
    public $nullValues = [''];

    /**
     * The validation rules for creation and updates.
     *
     * @var array
     */
    public $rules = [];

    /**
     * The validation rules for creation.
     *
     * @var array
     */
    public $creationRules = [];

    /**
     * The validation rules for updates.
     *
     * @var array
     */
    public $updateRules = [];

    /**
     * The callback to be used to resolve the field's value.
     *
     * @var \Closure
     */
    protected $resolveCallback;

    /**
     * The callback to be used to hydrate the model attribute.
     *
     * @var callable
     */
    protected $fillCallback;

    /**
     * The callback to be used to clean fill value.
     *
     * @var callable
     */
    protected $cleanCallback;

    /**
     * The callback to be used for computed field.
     *
     * @var callable
     */
    protected $computedCallback;

    /**
     * The callback used to determine if the field is readonly.
     *
     * @var Closure
     */
    protected $readonlyCallback;

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|callable|null  $attribute
     * @param  callable|null  $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        $this->name            = $name;
        $this->resolveCallback = $resolveCallback;

        if ($attribute instanceof Closure ||
            (is_callable($attribute) && is_object($attribute))) {
            $this->computedCallback = $attribute;
            $this->attribute        = '__computed__';
        } else {
            /** @noinspection ProperNullCoalescingOperatorUsageInspection */
            $this->attribute = $attribute ?? str_replace(' ', '_', Str::lower($name));
        }
    }

    /**
     * Display the field.
     *
     * @return HtmlString|string
     */
    public function render()
    {
        $request = app('request');

        $attributes = collect($allAttributes = $this->getAttributes())
            ->only(array_merge($this->universalAttributes, $this->inlineAttributes))
            ->all();

        $attributes['class'] = classnames(
            $attributes['class'] ?? '',
            ['is-invalid' => $this->hasError()]
        );

        if ($this->isReadonly($request)) {
            $attributes['readonly'] = 'readonly';
        }

        return new HtmlString(
            view($this->getView(), array_merge($allAttributes, [
                'old'        => $this->getOldValue(),
                'oldName'    => $this->getOldName(),
                'typeForm'   => $this->layout ?: self::LAYOUT_VERTICAL,
                'attributes' => $attributes,
            ]))->withErrors(session()->get('errors', app(ViewErrorBag::class)))->render()
        );
    }

    /**
     * Set the field value.
     *
     * @param  mixed  $value
     * @return $this
     */
    public function value($value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Resolve the field's value.
     *
     * @param  mixed  $resource
     * @param  string|null  $attribute
     * @return void
     */
    public function resolve($resource, $attribute = null)
    {
        $attribute = $attribute ?? (string) $this->attribute;

        if ($attribute === '__computed__') {
            $this->value = call_user_func($this->computedCallback, $resource);

            return;
        }

        if (!$this->resolveCallback) {
            $this->value = $this->resolveAttribute($resource, $attribute);
        } elseif (is_callable($this->resolveCallback)) {
            $value = data_get($resource, str_replace('->', '.', $attribute), $placeholder = new \stdClass());

            if ($value !== $placeholder) {
                $this->value = call_user_func($this->resolveCallback, $value, $resource);
            }
        }
    }

    /**
     * Define the callback that should be used to resolve the field's value.
     *
     * @param  callable  $resolveCallback
     * @return $this
     */
    public function resolveUsing(callable $resolveCallback)
    {
        $this->resolveCallback = $resolveCallback;

        return $this;
    }

    /**
     * Resolve the given attribute from the given resource.
     *
     * @param  mixed  $resource
     * @param  string  $attribute
     * @return mixed
     */
    protected function resolveAttribute($resource, $attribute)
    {
        return data_get($resource, str_replace('->', '.', $attribute));
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  Request  $request
     * @param  object  $model
     * @return mixed
     */
    public function fill(Request $request, $model)
    {
        return $this->fillInto($request, $model, $this->attribute);
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  Request  $request
     * @param  object  $model
     * @return mixed
     */
    public function fillForAction(Request $request, $model)
    {
        return $this->fill($request, $model);
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  Request  $request
     * @param  object  $model
     * @param  string  $attribute
     * @param  string|null  $requestAttribute
     * @return mixed
     */
    public function fillInto(Request $request, $model, $attribute, $requestAttribute = null)
    {
        return $this->fillAttribute($request, $requestAttribute ?? $this->attribute, $model, $attribute);
    }

    /**
     * Specify a callback that should be used to hydrate the model attribute for the field.
     *
     * @param  callable  $fillCallback
     * @return $this
     */
    public function fillUsing($fillCallback)
    {
        $this->fillCallback = $fillCallback;

        return $this;
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  Request  $request
     * @param  string  $requestAttribute
     * @param  object  $model
     * @param  string  $attribute
     * @return mixed
     */
    protected function fillAttribute(Request $request, $requestAttribute, $model, $attribute)
    {
        if (isset($this->fillCallback)) {
            return call_user_func($this->fillCallback, $request, $model, $attribute, $requestAttribute);
        }

        return $this->fillAttributeFromRequest($request, $requestAttribute, $model, $attribute);
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  Request  $request
     * @param  string  $requestAttribute
     * @param  object  $model
     * @param  string  $attribute
     * @return mixed
     */
    protected function fillAttributeFromRequest(Request $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $value = $request[$requestAttribute];

            $model->{$attribute} = $this->isNullValue($value) ? null : $this->cleanFillValue($value);
        }
    }

    /**
     * Clean the value for filling onto model.
     *
     * @param  mixed  $value
     * @return mixed
     */
    protected function cleanFillValue($value)
    {
        if ($this->cleanCallback) {
            return call_user_func($this->cleanCallback, $value);
        }

        return $value;
    }

    /**
     * Set the value cleaner.
     *
     * @param  callable  $callback
     * @return $this
     */
    public function cleanUsing(callable $callback)
    {
        $this->cleanCallback = $callback;

        return $this;
    }

    /**
     * Set the validation rules for the field.
     *
     * @param  callable|array|string  $rules
     * @return $this
     */
    public function rules($rules)
    {
        $this->rules = ($rules instanceof Rule || is_string($rules)) ? func_get_args() : $rules;

        return $this;
    }

    /**
     * Get the validation rules for this field.
     *
     * @param  Request  $request
     * @return array
     */
    public function getRules(Request $request)
    {
        return [
            $this->attribute => is_callable($this->rules)
                ? call_user_func($this->rules, $request)
                : $this->rules,
        ];
    }

    /**
     * Set the creation validation rules for the field.
     *
     * @param  callable|array|string  $rules
     * @return $this
     */
    public function creationRules($rules)
    {
        $this->creationRules = ($rules instanceof Rule || is_string($rules)) ? func_get_args() : $rules;

        return $this;
    }

    /**
     * Get the creation rules for this field.
     *
     * @param  Request  $request
     * @return array|string
     */
    public function getCreationRules(Request $request)
    {
        $rules = [
            $this->attribute => is_callable($this->creationRules)
                ? call_user_func($this->creationRules, $request)
                : $this->creationRules,
        ];

        return array_merge_recursive(
            $this->getRules($request),
            $rules
        );
    }

    /**
     * Set the creation validation rules for the field.
     *
     * @param  callable|array|string  $rules
     * @return $this
     */
    public function updateRules($rules)
    {
        $this->updateRules = ($rules instanceof Rule || is_string($rules)) ? func_get_args() : $rules;

        return $this;
    }

    /**
     * Get the update rules for this field.
     *
     * @param  Request  $request
     * @return array
     */
    public function getUpdateRules(Request $request)
    {
        $rules = [
            $this->attribute => is_callable($this->updateRules)
                ? call_user_func($this->updateRules, $request)
                : $this->updateRules,
        ];

        return array_merge_recursive(
            $this->getRules($request),
            $rules
        );
    }

    /**
     * Get the validation attribute for the field.
     *
     * @param  Request  $request
     * @return string
     */
    public function getValidationAttribute(Request $request)
    {
        return $this->validationAttribute ?? Str::singular($this->attribute);
    }

    /**
     * Indicate that the field should be nullable.
     *
     * @param  bool  $nullable
     * @param  array|Closure  $values
     * @return $this
     */
    public function nullable($nullable = true, $values = null)
    {
        $this->nullable = $nullable;

        if ($values !== null) {
            $this->nullValues($values);
        }

        return $this;
    }

    /**
     * Specify nullable values.
     *
     * @param  array|Closure  $values
     * @return $this
     */
    public function nullValues($values)
    {
        $this->nullValues = $values;

        return $this;
    }

    /**
     * Check value for null value.
     *
     * @param  mixed  $value
     * @return bool
     */
    protected function isNullValue($value)
    {
        if (!$this->nullable) {
            return false;
        }

        return is_callable($this->nullValues)
            ? ($this->nullValues)($value)
            : in_array($value, (array) $this->nullValues, true);
    }

    /**
     * Set the callback used to determin if the field is readonly.
     *
     * @param  Closure|bool  $callback
     * @return $this
     */
    public function readonly($callback = true)
    {
        $this->readonlyCallback = $callback;

        return $this;
    }

    /**
     * Determine if the field is readonly.
     *
     * @param  Request  $request
     * @return bool
     */
    public function isReadonly(Request $request)
    {
        return with($this->readonlyCallback, function ($callback) use ($request) {
            if ($callback === true || (is_callable($callback) && $callback($request))) {
                $this->setReadonlyAttribute();

                return true;
            }

            return false;
        });
    }

    /**
     * Set the field to a readonly field.
     *
     * @return $this
     */
    protected function setReadonlyAttribute()
    {
        $this->withMeta(['attributes' => ['readonly' => true]]);

        return $this;
    }

    /**
     * Get the field ID.
     *
     * @return string
     */
    public function getId(): string
    {
        return sprintf('field-%s', Str::slug($this->getNameForInput()));
    }

    /**
     * Get the input name.
     *
     * @return mixed
     */
    public function getNameForInput()
    {
        return str_replace(['.', '[]', '[', ']', '->'], ['_', '', '.', '', ''], $this->attribute);
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge([
            'component' => $this->component(),
            'name'      => $this->name,
            'attribute' => $this->attribute,
            'value'     => $this->value,
            'nullable'  => $this->nullable,
            'readonly'  => $this->isReadonly(request()),
        ], $this->meta());
    }
}
