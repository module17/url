<?php
declare(strict_types=1);

namespace Keppler\Url\Parser\Bags;

/**
 * Class QueryBag
 *
 * @package Url\Bags
 */
class QueryBag
{
    /**
     * @var array
     */
    protected $queryComponents = [];

    /**
     * @var string | null
     */
    protected $queryString = null;

    /**
     * @param string $query
     */
    public function buildQueryComponents(string $query): void
    {
        // If the needle is not found it means that we have only one value
        // TODO This could potentially lead to some errors down the line
        // TODO Check if there's a better way to find if a query actually exists
        if (false === strpos($query, '&')) {
            $this->queryComponents = explode('=', $query);
        } else {
            $components = explode('&', $query);

            array_map(function ($value) {
                $components = explode('=', $value);
                $this->queryComponents[$components[0]] = $components[1];
            }, $components);
        }

        $this->queryString = $query;
    }

    /**
     * @return string
     */
    public function raw(): string
    {
        return null !== $this->queryString ? $this->queryString : '';
    }

    /**
     * @return array
     * @throws \LogicException
     */
    public function first(): array
    {
        if (empty($this->queryComponents)) {
            throw new \LogicException("Cannot get first entry of an empty array");
        }

        return [key($this->queryComponents) => $this->queryComponents[key($this->queryComponents)]];
    }

    /**
     * @return array
     * @throws \LogicException
     */
    public function last(): array
    {
        if (empty($this->queryComponents)) {
            throw new \LogicException("Cannot get last entry of an empty array");
        }

        $array = $this->queryComponents;
        reset($array);
        end($array);

        return [key($array) => $this->queryComponents[key($array)]];
    }

    /**
     * @param string $component
     *
     * @return bool
     */
    public function has(string $component): bool
    {
        return array_key_exists($component, $this->queryComponents);
    }

    /**
     * @param string $component
     *
     * @return null|string
     */
    public function get(string $component): ?string
    {
        if (empty($this->queryComponents)) {
            throw new \LogicException("Query bag is empty");
        }

        return $this->has($component) ? $this->queryComponents[$component]
            : null;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->queryComponents;
    }

}
