<?php
	
use BulkWP\Tests\WPCore\WPCoreUnitTestCase;
	
 /**
 * Test BM_Metabox_Posts_TagToCategory class.
 */
class TagtocategoryTest extends WPCoreUnitTestCase {
	
	/**
	 * @var \BM_Metabox_Posts_TagToCategory
	 */
	protected $tag_to_category_metabox;
	
	public function setUp() {
		parent::setUp();
		
		$this->tag_to_category_metabox = new \BM_Metabox_Posts_TagToCategory();
	}
	
	/**
	 * Test basic case of moving tag to category.
	 */
	public function test_move_posts_from_tag_to_category() {
		// Create one tag and category.
		$tag = $this->factory->tag->create( array( 'name' => 'tag' ) );
		$cat = $this->factory->category->create( array( 'name' => 'cat' ) );
		
		// Create one post in tag and category.
		$post_tag = $this->factory->post->create( array( 'post_title' => 'post_tag' ) );
		wp_set_post_tags( $post_tag, 'tag' );
		
		$post_cat = $this->factory->post->create( array( 'post_title' => 'post_cat', 'post_category' => array( $cat ) ) );
		
		// Assert that tag has one post and category has one post.
		$posts_in_tag = $this->get_posts_by_tag( $tag );
		$posts_in_cat = $this->get_posts_by_category( $cat );
		
		$this->assertEquals( 1, count( $posts_in_tag ) );
		$this->assertEquals( 1, count( $posts_in_cat ) );
		
		// call our method.
		$options = array(
			'tag'   => $tag,
			'cat'   => $cat,
			'overwrite' => true,
		);
		$this->tag_to_category_metabox->move( $options );
		
		// Assert that tag 1 has no posts.
		$posts_in_tag = $this->get_posts_by_tag( $tag );
		$this->assertEquals( 0, count( $posts_in_tag ) );
		
		// Assert that category has two posts.
		$posts_in_cat = $this->get_posts_by_category( $cat );
		$this->assertEquals( 2, count( $posts_in_cat ) );
	}
	
	/**
	 * Test case of moving tag to category with overwrite.
	 */
	public function test_move_posts_from_tag_to_category_with_overwrite() {
		// Create two category and one tag.
		$tag = $this->factory->tag->create( array( 'name' => 'tag' ) );
		$cat = $this->factory->category->create( array( 'name' => 'cat' ) );
		$common_cat = $this->factory->category->create( array( 'name' => 'common_cat' ) );
		
		// Create one post in tag and category.
		// The post_tag will also have the common category.
		$post_tag = $this->factory->post->create( array( 'post_title' => 'post_tag' ) );
		wp_set_post_tags( $post_tag, 'tag' );
		
		$post_cat = $this->factory->post->create( array( 'post_title' => 'post_cat', 'post_category' => array( $cat, $common_cat ) ) );
		
		// Assert that each tag and categories has one post.
		$posts_in_tag = $this->get_posts_by_tag( $tag );
		$posts_in_cat = $this->get_posts_by_category( $cat );
		$posts_in_common_cat = $this->get_posts_by_category( $common_cat );
		
		$this->assertEquals( 1, count( $posts_in_tag ) );
		$this->assertEquals( 1, count( $posts_in_cat ) );
		$this->assertEquals( 1, count( $posts_in_common_cat ) );
		
		// call our method.
		$options = array(
			'tag'   => $tag,
			'cat'   => $cat,
			'overwrite' => true,
		);
		$this->tag_to_category_metabox->move( $options );
		
		// Assert that tag has no posts.
		$posts_in_tag = $this->get_posts_by_tag( $tag );
		$this->assertEquals( 0, count( $posts_in_tag ) );

		// Assert that category has two posts.
		$posts_in_cat = $this->get_posts_by_category( $cat );
		$this->assertEquals( 2, count( $posts_in_cat ) );
		
		// Assert that common category has no posts.
		$posts_in_common_cat = $this->get_posts_by_tag( $common_cat );
		$this->assertEquals( 0, count( $posts_in_common_cat ) );
	}
	
	/**
	 * Test case of moving tag to category without overwrite.
	 */
	public function test_move_posts_from_tag_to_category_without_overwrite() {
		// Create two category and one tag.
		$tag = $this->factory->tag->create( array( 'name' => 'tag' ) );
		$cat = $this->factory->category->create( array( 'name' => 'cat' ) );
		$common_cat = $this->factory->category->create( array( 'name' => 'common_cat' ) );
		
		// Create one post in tag and category.
		// The post_tag will also have the common category.
		$post_tag = $this->factory->post->create( array( 'post_title' => 'post_tag' ) );
		wp_set_post_tags( $post_tag, 'tag' );
		
		$post_cat = $this->factory->post->create( array( 'post_title' => 'post_cat', 'post_category' => array( $cat, $common_cat ) ) );
		
		// Assert that each tag and categories has one post.
		$posts_in_tag = $this->get_posts_by_tag( $tag );
		$posts_in_cat = $this->get_posts_by_category( $cat );
		$posts_in_common_cat = $this->get_posts_by_category( $common_cat );
		
		$this->assertEquals( 1, count( $posts_in_tag ) );
		$this->assertEquals( 1, count( $posts_in_cat ) );
		$this->assertEquals( 1, count( $posts_in_common_cat ) );
		
		// call our method.
		$options = array(
			'tag'   => $tag,
			'cat'   => $cat,
			'overwrite' => false,
		);
		$this->tag_to_category_metabox->move( $options );
		
		// Assert that tag has no posts.
		$posts_in_tag = $this->get_posts_by_tag( $tag );
		$this->assertEquals( 0, count( $posts_in_tag ) );
		
		// Assert that category has two posts.
		$posts_in_cat = $this->get_posts_by_category( $cat );
		$this->assertEquals( 2, count( $posts_in_cat ) );
		
		// Assert that common category has one posts.
		$posts_in_common_cat = $this->get_posts_by_category( $common_cat );
		$this->assertEquals( 1, count( $posts_in_common_cat ) );
	}
}