<?php
namespace Intraxia\Jaxion\Test\Model;

use Intraxia\Jaxion\Model\Post;
use Mockery;
use WP_Mock;

class PostTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Mockery\Mock
     */
    protected $mockPost;

    public function setUp()
    {
        parent::setUp();
        $this->mockPost = Mockery::mock('overload:WP_Post');
    }

    public function testShouldSetIDFromWPPost()
    {
        $this->mockPost->ID = 1;

        $post = new Post(array('post' => $this->mockPost));

        $this->assertEquals(1, $post->ID);
    }

    public function testShouldSetWPPostID()
    {
        $post = new Post(array());
        $post->ID = 1;

        $this->assertEquals(1, $post->getUnderlyingPost()->ID);
    }

    public function testShouldSetAuthorFromWPPost()
    {
        $this->mockPost->post_author = 'mAAdhaTTah';

        $post = new Post(array('post' => $this->mockPost));

        $this->assertEquals('mAAdhaTTah', $post->author);
    }

    public function testShouldSetWPPostAuthor()
    {
        $post = new Post(array());
        $post->author = '1';

        $this->assertEquals('1', $post->getUnderlyingPost()->post_author);
    }

    public function testShouldSetSlugFromWPPost()
    {
        $this->mockPost->post_name = 'post-name';

        $post = new Post(array('post' => $this->mockPost));

        $this->assertEquals('post-name', $post->slug);
    }

    public function testShouldSetWPPostSlug()
    {
        $post = new Post(array());
        $post->slug = 'post-name';

        $this->assertEquals('post-name', $post->getUnderlyingPost()->post_name);
    }

    public function testShouldNotSetTypeFromWPPost()
    {
        $this->mockPost->post_type = 'some-cpt';

        $post = new Post(array('post' => $this->mockPost));

        $this->assertEquals('post', $post->type);
    }

    public function testShouldNotSetWPPostType()
    {
        $post = new Post(array());
        $post->type = 'some-cpt';

        $this->assertEquals('post', $post->getUnderlyingPost()->post_type);
    }

    public function testShouldSetTitleFromWPPost()
    {
        $this->mockPost->post_title = 'Post name';

        $post = new Post(array('post' => $this->mockPost));

        $this->assertEquals('Post name', $post->title);
    }

    public function testShouldSetWPPostTitle()
    {
        $post = new Post(array());
        $post->title = 'Post name';

        $this->assertEquals('Post name', $post->getUnderlyingPost()->post_title);
    }

    public function testShouldSetPublishDateFromWPPost()
    {
        $this->mockPost->post_date_gmt = '2015-12-26 00:00:00';

        $post = new Post(array('post' => $this->mockPost));

        $this->assertEquals('2015-12-26 00:00:00', $post->publish_date);
    }

    public function testShouldSetWPPostPublishDate()
    {
        $post = new Post(array());
        $post->publish_date = '2015-12-26 00:00:00';

        $this->assertEquals('2015-12-26 00:00:00', $post->getUnderlyingPost()->post_date_gmt);
    }

    public function testShouldSetContentFromWPPost()
    {
        $this->mockPost->post_content = 'Post content';

        $post = new Post(array('post' => $this->mockPost));

        $this->assertEquals('Post content', $post->content);
    }

    public function testShouldSetWPPostContent()
    {
        $post = new Post(array());
        $post->content = 'Post content';

        $this->assertEquals('Post content', $post->getUnderlyingPost()->post_content);
    }

    public function testShouldSetExcerptFromWPPost()
    {
        $this->mockPost->post_excerpt = 'Post excerpt';

        $post = new Post(array('post' => $this->mockPost));

        $this->assertEquals('Post excerpt', $post->excerpt);
    }

    public function testShouldSetWPPostExcerpt()
    {
        $post = new Post(array());
        $post->excerpt = 'Post excerpt';

        $this->assertEquals('Post excerpt', $post->getUnderlyingPost()->post_excerpt);
    }

    public function testShouldIgnoreRandomProperties()
    {
        $post = new Post(array());
        $post->randomProperty = 'Random property';

        $this->setExpectedException('\Intraxia\Jaxion\Model\PropertyDoesNotExistException');

        $post->randomProperty;
    }

    public function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }
}
