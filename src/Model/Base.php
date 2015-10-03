<?php
namespace Intraxia\Jaxion\Model;

use Intraxia\Jaxion\Utility\Str;
use stdClass;
use WP_Post;

/**
 * Class Base
 *
 * Shared model methods and properties, allowing models
 * to transparently map some attributes to an underlying WP_Post
 * object and others to postmeta or a custom table.
 *
 * @package Intraxia\Jaxion
 * @subpackage Model
 * @since 0.1.0
 */
abstract class Base
{
    /**
     * Model attributes array.
     *
     * @var array
     */
    private $attributes = array(
        'table' => array(),
        'post' => null,
    );

    /**
     * Which custom table does this model uses.
     *
     * If false, model wil fall back to postmeta.
     *
     * @var bool|string
     */
    protected $table = false;

    /**
     * Whether to use WP_Post mappings.
     *
     * @var bool
     */
    protected $post = true;

    /**
     * Post type/
     *
     * @var bool|string
     */
    protected $type = false;
    /**
     * Properties which are allowed to be set on the model.
     *
     * If this array is empty, any attributes can be set on the model
     *
     * @var string[]
     */
    protected $fillable = array();

    /**
     * Constructs a new model with provided attributes.
     *
     * If 'post' is passed as one of the attributes
     *
     * @param array <string, mixed> $attributes
     */
    public function __construct(array $attributes)
    {
        foreach ($attributes as $name => $value) {
            $this->setAttribute($name, $value);
        }

        if ($this->post && !isset($this->attributes['post'])) {
            $this->createDefaultPost();
        }
    }

    /**
     * Get the model's attributes.
     *
     * Returns the array of for the model that will either need to be
     * saved in postmeta or a separate table.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes['table'];
    }

    /**
     * Get the model's underlying post.
     *
     * Returns the underlying WP_Post object for the model, representing
     * the data that will be save in the wp_posts table.
     *
     * @return false|WP_Post
     */
    public function getUnderlyingPost()
    {
        if (isset($this->attributes['post'])) {
            return $this->attributes['post'];
        }

        return false;
    }

    /**
     * Magic __set method.
     *
     * Passes the name and value to setAttribute, which is where the magic happens.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
        $this->setAttribute($name, $value);
    }

    /**
     * Sets the model attributes.
     *
     * Checks whether the model attribute can be set, check if it
     * maps to the WP_Post property, otherwise, assigns it to the
     * table attribute array.
     *
     * @param string $name
     * @param mixed  $value
     */
    private function setAttribute($name, $value)
    {
        if ('post' === $name) {
            $this->overridePost($value);

            return;
        }

        if (!$this->isFillable($name)) {
            return;
        }

        if ($method = $this->hasMapMethod($name)) {
            $this->attributes['post']->{$this->{$method}()} = $value;

            return;
        }

        $this->attributes['table'][$name] = $value;
    }

    /**
     * Checks if a given attribute is mass-fillable.
     *
     * Returns true if the attribute can be filled, false if it can't.
     *
     * @param string $name
     *
     * @return bool
     */
    private function isFillable($name)
    {
        // `type` is not fillable at all.
        if ('type' === $name) {
            return false;
        }

        // If the `$fillable` array hasn't been defined, pass everything.
        if (!$this->fillable) {
            return true;
        }

        return in_array($name, $this->fillable);
    }

    /**
     * Overrides the current WP_Post with a provided one.
     *
     * Resets the post's default values and stores it in the attributes.
     *
     * @param WP_Post $value
     */
    private function overridePost(WP_Post $value)
    {
        $this->attributes['post'] = $this->enforcePostDefaults($value);
    }

    /**
     * Create and set with a new blank post.
     *
     * Creates a new WP_Post object, assigns it the default attributes,
     * and stores it in the attributes.
     */
    private function createDefaultPost()
    {
        $this->attributes['post'] = $this->enforcePostDefaults(new WP_Post(new stdClass));
    }

    /**
     * Enforces values on the post that can't change.
     *
     * Primarily, this is used to make sure the post_type always maps
     * to the model's "$type" property, but this can all be overridden
     * by the developer to enforce other values in the model.
     *
     * @param WP_Post $post
     *
     * @return WP_Post
     */
    protected function enforcePostDefaults(WP_Post $post)
    {
        if (is_string($this->type)) {
            $post->post_type = $this->type;
        }

        return $post;
    }

    /**
     * Magic __get method.
     *
     * Passes the name and value to getAttribute, which is where the magic happens.
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->getAttribute($name);
    }

    /**
     * Retrieves the model attribute.
     *
     * If the attribute maps to the WP_Post, retrieves it from there.
     * Otherwise, checks if it's in the attributes array
     *
     * @param string $name
     *
     * @return mixed
     *
     * @throws PropertyDoesNotExistException If property isn't found.
     */
    protected function getAttribute($name)
    {
        if ('type' === $name) {
            return $this->type;
        }

        if ($method = $this->hasMapMethod($name)) {
            $value = $this->attributes['post']->{$this->{$method}()};
        } else {
            if (!isset($this->attributes['table'][$name])) {
                throw new PropertyDoesNotExistException;
            }

            $value = $this->attributes['table'][$name];
        }

        return $value;

    }

    /**
     * Checks whether the attribute has a map method.
     *
     * This is used to determine whether the attribute maps to a
     * property on the underlying WP_Post object. Returns the
     * method if one exists, returns false if it doesn't.
     *
     * @param string $name
     *
     * @return false|string
     */
    protected function hasMapMethod($name)
    {
        $method = 'map' . Str::studly($name);

        if (method_exists($this, $method)) {
            return $method;
        }

        return false;
    }
}
