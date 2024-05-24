<?php

namespace Core\Screens;

use RuntimeException;
use Core\Database\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class ScreenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Get the current screen controller.
     *
     * @return Screen
     */
    public function screen(): Screen
    {
        return tap($this->route()->getController(), function ($screen) {
            abort_if(!$screen instanceof Screen, 404);
        });
    }

    /**
     * Returns the new model instance.
     *
     * @return Model
     */
    public function model(): Model
    {
        $resource = $this->resource();

        return $resource::newModel();
    }

    /**
     * Get the model respository from the screen.
     *
     * @return Repository
     * @throws RuntimeException
     */
    public function resource(): Repository
    {
        $screen = $this->screen();

        if ($screen instanceof HasRepository) {
            return $screen->getRepository();
        }

        throw new RuntimeException(
            sprintf('The screen [%s] must implement the interface [%s]', get_class($screen), HasRepository::class)
        );
    }
}
