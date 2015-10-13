<?php
namespace Intraxia\Jaxion\Http;

use Intraxia\Jaxion\Contract\Http\Filter as FilterContract;

/**
 * Class Filter
 *
 * Generates the rules used by the WP-API to validate and sanitize and
 *
 * @package Intraxia\Jaxion
 * @subpackage Http
 */
class Filter implements FilterContract
{
    /**
     * Filter rules.
     *
     * @var array
     */
    protected $rules;

    /**
     * Instantiates a new filter with the provided rules array.
     *
     * @param array $rules
     */
    public function __construct($rules = array())
    {
        $this->rules = $rules;
    }

    /**
     * Generates argument rules.
     *
     * Returns an array matching the WP-API format for argument rules,
     * including sanitization, validation, required, or defaults.
     *
     * @return array
     */
    public function rules()
    {
        $args = array();

        foreach ($this->rules as $arg => $validation) {
            if (!$validation || !is_string($validation)) {
                continue;
            }

            $args[$arg] = $this->parseValidation($validation);
        }

        return $args;
    }

    /**
     * Parses a validation string into a WP-API compatible rule.
     *
     * @param string $validation
     * @return array
     */
    protected function parseValidation($validation)
    {
        $validation = explode('|', $validation);

        $rules = array();

        foreach ($validation as $rule) {
            if (0 === strpos($rule, 'default')) {
                $ruleArr = explode(':', $rule);

                $rules['default'] = count($ruleArr) === 2 ? array_pop($ruleArr) : '';
            }

            switch ($rule) {
                case 'required':
                    $rules['required'] = true;
                    break;
                case 'integer':
                    $rules['validate_callback'] = array($this, 'validateInteger');
                    $rules['sanitize_callback'] = array($this, 'makeInteger');
                    break;
            }
        }

        return $rules;
    }

    /**
     * Validate that provided value is an integer.
     *
     * @param mixed $value
     * @return bool
     */
    public function validateInteger($value)
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    /**
     * Casts a provided value to an integer.
     *
     * @param mixed $value
     * @return int
     */
    public function makeInteger($value)
    {
        return (int) $value;
    }
}
