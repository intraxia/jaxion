<?php
namespace Intraxia\Jaxion;

class Loader
{
    /**
     * Array of action hooks to attach.
     *
     * @var array[]
     */
    protected $actions = array();

    /**
     * Array of filter hooks to attach.
     *
     * @var array[]
     */
    protected $filters = array();

    /**
     * Registers the Application Services with their relevant hooks.
     *
     * Loops through all the services, checks for the presence of
     * an `actions` or `filters` property on each service, and, if present,
     * registers the declared actions and filters with WordPress.
     *
     * @param Application $app
     */
    public function register(Application $app)
    {
        foreach ($app as $name => $service) {
            if (property_exists($service, 'actions') && is_array($service->actions)) {
                $this->addActions($service);
            }

            if (property_exists($service, 'filters') && is_array($service->filters)) {
                $this->addFilters($service);
            }
        }

        add_action('plugins_loaded', array($this, 'run'));
    }

    /**
     * Registers a single Service's actions with WordPress.
     *
     * @param object $service
     */
    protected function addActions($service)
    {
        foreach ($service->actions as $action) {
            $this->actions = $this->add(
                $this->actions,
                $action['hook'],
                $service,
                $action['method'],
                isset($action['priority']) ? $action['priority'] : 10,
                isset($action['args']) ? $action['args'] : 1
            );
        }
    }

    /**
     * Registers a single Service's filters with WordPress.
     *
     * @param object $service
     */
    protected function addFilters($service)
    {
        foreach ($service->filters as $hook => $filter) {
            $this->filters = $this->add(
                $this->filters,
                $filter['hook'],
                $service,
                $filter['method'],
                isset($filter['priority']) ? $filter['priority'] : 10,
                isset($filter['args']) ? $filter['args'] : 1
            );
        }
    }

    /**
     * Utility to register the actions and hooks into a single collection.
     *
     * @param array $hooks
     * @param string $hook
     * @param object $service
     * @param string $method
     * @param int $priority
     * @param int $accepted_args
     * @return array
     */
    protected function add($hooks, $hook, $service, $method, $priority, $accepted_args) {
        $hooks[] = array(
            'hook' => $hook,
            'service' => $service,
            'method' => $method,
            'priority' => $priority,
            'args' => $accepted_args,
        );

        return $hooks;
    }

    /**
     * Register all the actions and filters with WordPress.
     *
     * Loops through all the registered actions/filters and fires add_* for each of
     * them respectively.
     */
    public function run()
    {
        foreach ($this->actions as $action) {
            add_action($action['hook'], array($action['service'], $action['method']), $action['priority'], $action['args']);
        }

        foreach ($this->filters as $filter) {
            add_filter($filter['hook'], array($filter['service'], $filter['method']), $filter['priority'], $filter['args']);
        }
    }
}
