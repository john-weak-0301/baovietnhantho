<?php

namespace Core\Screens;

use Core\Elements\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait ResolvesCards
{
    /**
     * Get the cards available on the entity.
     *
     * @return array
     */
    protected function cards(): array
    {
        return [];
    }

    /**
     * Get the cards for the given request.
     *
     * @return Collection
     */
    protected function resolveCards(): Collection
    {
        return collect(array_values($this->cards()));
    }

    /**
     * Get the cards that are available for the given request.
     *
     * @param  Request  $request
     * @return Collection
     */
    protected function availableCards(Request $request = null): Collection
    {
        $request = $request ?? app(Request::class);

        return $this->resolveCards()->filter(function (Card $card) use ($request) {
            return $card->authorize($request);
        })->values();
    }
}
