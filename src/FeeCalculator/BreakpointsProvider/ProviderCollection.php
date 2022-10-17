<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\FeeCalculator\BreakpointsProvider;

use Error;

final class ProviderCollection
{
    private array $providers;

    public function add(ProviderInterface $provider): void
    {
        $this->providers[$provider::class] = $provider;
    }

    public function get(string $provider): ProviderInterface
    {
        if (!isset($this->providers[$provider])) {
            throw new Error(sprintf('The breakpoints provider [ %s ] has not been registered.', $provider));
        }

        return $this->providers[$provider];
    }
}