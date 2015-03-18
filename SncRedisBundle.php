<?php

/*
 * This file is part of the SncRedisBundle package.
 *
 * (c) Henrik Westphal <henrik.westphal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Snc\RedisBundle;

use Snc\RedisBundle\DependencyInjection\Compiler\LoggingPass;
use Snc\RedisBundle\DependencyInjection\Compiler\MonologPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\IntrospectableContainerInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * SncRedisBundle
 */
class SncRedisBundle extends Bundle
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new LoggingPass());
        $container->addCompilerPass(new MonologPass());
    }

    public function shutdown()
    {
        foreach ($this->container->getParameter('snc_redis.clients') as $id) {
            if (!$this->container instanceof IntrospectableContainerInterface || $this->container->initialized($id)) {
                $this->container->get($id)->disconnect();
            }
        }
    }
}
