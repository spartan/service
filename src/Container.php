<?php

namespace Spartan\Service;

use Psr\Container\ContainerInterface;
use Spartan\Service\Exception\ContainerException;
use Spartan\Service\Exception\NotFoundException;

/**
 * Container Service
 *
 * @package Spartan\Service
 * @author  Iulian N. <iulian@spartanphp.com>
 * @license LICENSE MIT
 */
class Container implements ContainerInterface
{
    const SINGLETON = 1;
    const PROTOTYPE = 2;
    const LOGGING   = 4;
    const INJECT    = '@inject';
    const DEFAULT   = '@default';

    /**
     * @var string[]
     */
    public array $logs = [];

    protected int $options = 0;

    /**
     * @var mixed[]
     */
    protected array $singletons = [];

    /**
     * @var mixed[]
     */
    protected array $prototypes = [];

    /**
     * Container constructor.
     *
     * @param mixed[] $singletons
     * @param mixed[] $prototypes
     * @param int     $options
     */
    public function __construct(
        array $singletons = [],
        array $prototypes = [],
        int $options = self::SINGLETON
    ) {
        $this->singletons = $singletons;
        $this->prototypes = $prototypes;
        $this->options    = $options;
    }

    /**
     * @param mixed[] $singletons
     * @param mixed[] $prototypes
     *
     * @return $this
     */
    public function withBindings(array $singletons = [], array $prototypes = [])
    {
        $this->singletons = $singletons + $this->singletons;
        $this->prototypes = $prototypes + $this->prototypes;

        return $this;
    }

    /**
     * @param mixed $id
     *
     * @return array[]
     * @throws \ReflectionException
     * @throws ContainerException
     * @throws NotFoundException
     */
    public function reflect($id): array
    {
        try {
            $reflected = new \ReflectionClass($id);
        } catch (\Exception $e) {
            throw new NotFoundException('Could not locate class ' . json_encode($id));
        }

        if (!$reflected->isInstantiable()) {
            throw new ContainerException('Class is not instantiable' . json_encode($id));
        }

        $constructor = $reflected->getConstructor();

        if ($constructor === null) {
            $this->prototypes[$id] = [];

            return [];
        }

        $parent = $constructor->class;
        if (isset($this->prototypes[$parent])) {
            return $this->prototypes[$parent];
        }

        $definition = [];
        foreach ($constructor->getParameters() as $param) {
            $definition[$param->getName()] = self::parameterValue($param);
        }

        $this->prototypes[$parent] = $definition;

        if ($this->options & self::LOGGING) {
            $this->logs[] = $parent;
        }

        return $definition;
    }

    /**
     * @param mixed $id
     *
     * @return mixed
     * @throws ContainerException
     * @throws NotFoundException
     * @throws \ReflectionException
     */
    public function create($id)
    {
        $definition = $this->prototypes[$id] ?? null;

        if (is_string($definition)) {
            return $this->create($definition);
        }

        if ($definition === null) {
            $definition = $this->reflect($id);
        }

        if ($definition instanceof \Closure) {
            return $definition($this);
        }

        // faster than ReflectionClass::newInstanceArgs in php7.2
        return new $id(...$this->parameters($definition));
    }

    /*
     * PSR-11
     */

    /**
     * @param string $id
     *
     * @return mixed|null
     * @throws ContainerException
     * @throws NotFoundException
     * @throws \ReflectionException
     */
    public function get($id)
    {
        if (isset($this->singletons[$id]) && $this->options & self::SINGLETON) {
            $service = $this->singletons[$id];
            if ($service instanceof \Closure) {
                $service               = $service($this);
                $this->singletons[$id] = $service;
            } elseif (is_string($service)) {
                $service               = $this->get($service);
                $this->singletons[$id] = $service;
            }

            return $service;
        }

        return $this->create($id);
    }

    /**
     * @inheritDoc
     */
    public function has($id)
    {
        return isset($this->singletons[$id])
            || isset($this->prototypes[$id])
            || class_exists($id);
    }

    /*
     * Helpers
     */

    /**
     * @param mixed[] $definition
     *
     * @return string[]
     * @throws ContainerException
     * @throws NotFoundException
     * @throws \ReflectionException
     */
    public function parameters(array $definition): array
    {
        $parameters = [];

        foreach ($definition as $name => $item) {
            if (is_array($item)) {
                if (isset($item[static::INJECT])) {
                    $id = $item[static::INJECT];
                    if (!$this->isPhpType($id)) {
                        $parameters[$name] = $this->get($id);
                        continue;
                    }
                }

                if (isset($item[static::DEFAULT])) {
                    $parameters[$name] = $item[static::DEFAULT];
                    continue;
                }
            }

            $parameters[$name] = $item;
        }

        return array_values($parameters);
    }

    /**
     * Check if the parameter type is reserved
     *
     * @param string $id
     *
     * @return bool
     */
    public function isPhpType(string $id)
    {
        return in_array($id, ['bool', 'boolean', 'int', 'integer', 'float', 'double', 'string', 'array']);
    }

    /**
     * @param \ReflectionParameter $param
     *
     * @return array|mixed|null
     * @throws \ReflectionException
     */
    public static function parameterValue(\ReflectionParameter $param)
    {
        $result = [];

        if ($param->hasType()) {
            $result[static::INJECT] = $param->getType()->getName();
        }

        if ($param->isDefaultValueAvailable()) {
            $result[static::DEFAULT] = $param->getDefaultValue();
        }

        return $result ?: null;
    }
}
