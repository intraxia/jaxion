<?php
namespace Intraxia\Jaxion\Model;

/**
 * Class Post
 *
 * Default implementation of the Base model class, mapping a set
 * of clearer naming conventions to a basic underlying WP_Post
 * object.
 *
 * @package Intraxia\Jaxion
 * @subpackage Model
 */
class Post extends Base
{
    /**
     * Post type this model maps to.
     *
     * @var string
     */
    protected $type = 'post';

    /**
     * Fillable attributes for the model.
     *
     * @var array
     */
    protected $fillable = array('ID', 'author', 'slug', 'title', 'publish_date', 'content', 'excerpt');

    /**
     * ID property maps to ID.
     *
     * @return string
     */
    protected function mapID()
    {
        return 'ID';
    }

    /**
     * Author property maps to post_author.
     *
     * @return string
     */
    protected function mapAuthor()
    {
        return 'post_author';
    }

    /**
     * Slug property maps to post_name.
     *
     * @return string
     */
    protected function mapSlug()
    {
        return 'post_name';
    }

    /**
     * Title property maps to post_title.
     *
     * @return string
     */
    protected function mapTitle()
    {
        return 'post_title';
    }

    /**
     * Publish date property maps to post_date_gmt.
     *
     * @return string
     */
    protected function mapPublishDate()
    {
        return 'post_date_gmt';
    }

    /**
     * Content property maps to post_content.
     *
     * @return string
     */
    protected function mapContent()
    {
        return 'post_content';
    }

    /**
     * Excerpt property maps to post_excerpt.
     *
     * @return string
     */
    protected function mapExcerpt()
    {
        return 'post_excerpt';
    }
}
