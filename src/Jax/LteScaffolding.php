<?php

namespace Lar\LteAdmin\Jax;

use Lar\LJS\JaxExecutor;
use Lar\LteAdmin\Events\Scaffold;

/**
 * Class LteScaffolding
 * @package Lar\LteAdmin\Jax
 */
class LteScaffolding extends JaxExecutor
{
    /**
     * @return bool
     */
    public function access()
    {
        return !\LteAdmin::guest() && \LteAdmin::user()->isRoot();
    }

    /**
     * @param  array  $data
     */
    public function __invoke(array $data)
    {
        event(new Scaffold($data));
    }
}