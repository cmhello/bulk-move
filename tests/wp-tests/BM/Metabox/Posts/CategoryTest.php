<?php

/**
 * Test BM_Metabox_Posts_Category class.
 *
 * TODO: Add tests for default category.
 */
class CategoryTest extends \BM_TestCase {

	/**
	 * @var \BM_Metabox_Posts_Category
	 */
	protected $category_metabox;

	public function setUp() {
		parent::setUp();

		$this->category_metabox = new \BM_Metabox_Posts_Category();
	}

	/**
	 * Test basic case of moving categories.
	 */
	public function test_move_posts_from_one_cat_to_another() {
		// Create two categories.
		$cat1 = $this->factory->category->create( array( 'name' => 'cat1' ) );
		$cat2 = $this->factory->category->create( array( 'name' => 'cat2' ) );

		// Create one post in each category.
		$post1 = $this->factory->post->create( array( 'post_title' => 'post1', 'post_category' => array( $cat1 ) ) );
		$post2 = $this->factory->post->create( array( 'post_title' => 'post2', 'post_category' => array( $cat2 ) ) );

		// Assert that each category has one post.
		$posts_in_cat1 = $this->get_posts_by_category( $cat1 );
		$posts_in_cat2 = $this->get_posts_by_category( $cat2 );

		$this->assertEquals( count( $posts_in_cat1 ), 1 );
		$this->assertEquals( count( $posts_in_cat2 ), 1 );

		// call our method.
		$options = array(
			'old_cat'   => $cat1,
			'new_cat'   => $cat2,
			'overwrite' => true,
		);
		$this->category_metabox->move( $options );

		// Assert that category 1 has no posts.
		$posts_in_cat1 = $this->get_posts_by_category( $cat1 );
		$this->assertEquals( count( $posts_in_cat1 ), 0 );

		// Assert that category 2 has two posts.
		$posts_in_cat2 = $this->get_posts_by_category( $cat2 );
		$this->assertEquals( count( $posts_in_cat2 ), 2 );
	}

	/**
	 * Test moving posts from one category to another with overwrite.
	 */
	public function test_move_posts_from_one_cat_to_another_with_overwrite() {
		// Create two categories and a common category.
		$cat1 = $this->factory->category->create( array( 'name' => 'cat1' ) );
		$cat2 = $this->factory->category->create( array( 'name' => 'cat2' ) );
		$common_cat = $this->factory->category->create( array( 'name' => 'common_cat' ) );

		// Create one post in each category.
		// The first post will also have the common category.
		$post1 = $this->factory->post->create( array( 'post_title' => 'post1', 'post_category' => array( $cat1, $common_cat ) ) );
		$post2 = $this->factory->post->create( array( 'post_title' => 'post2', 'post_category' => array( $cat2 ) ) );

		// Assert that each category has one post.
		$posts_in_cat1 = $this->get_posts_by_category( $cat1 );
		$posts_in_cat2 = $this->get_posts_by_category( $cat2 );
		$posts_in_common_cat = $this->get_posts_by_category( $common_cat );

		$this->assertEquals( count( $posts_in_cat1 ), 1 );
		$this->assertEquals( count( $posts_in_cat2 ), 1 );
		$this->assertEquals( count( $posts_in_common_cat ), 1 );

		// Invoke our method.
		$options = array(
			'old_cat'   => $cat1,
			'new_cat'   => $cat2,
			'overwrite' => true,
		);
		$this->category_metabox->move( $options );

		// Assert that category 1 has no posts.
		$posts_in_cat1 = $this->get_posts_by_category( $cat1 );
		$this->assertEquals( count( $posts_in_cat1 ), 0 );

		// Assert that common category has no posts.
		$posts_in_common_cat = $this->get_posts_by_category( $common_cat );
		$this->assertEquals( count( $posts_in_common_cat ), 0 );

		// Assert that category 2 has two posts.
		$posts_in_cat2 = $this->get_posts_by_category( $cat2 );
		$this->assertEquals( count( $posts_in_cat2 ), 2 );
	}

	/**
	 * Test Moving category without overwrite.
	 */
	public function test_move_posts_from_one_cat_to_another_without_overwrite() {
		// Create two categories and a common category.
		$cat1 = $this->factory->category->create( array( 'name' => 'cat1' ) );
		$cat2 = $this->factory->category->create( array( 'name' => 'cat2' ) );
		$common_cat = $this->factory->category->create( array( 'name' => 'common_cat' ) );

		// Create one post in each category.
		// The first post will also have the common category.
		$post1 = $this->factory->post->create( array( 'post_title' => 'post1', 'post_category' => array( $cat1, $common_cat ) ) );
		$post2 = $this->factory->post->create( array( 'post_title' => 'post2', 'post_category' => array( $cat2 ) ) );

		// Assert that each cateogry has one post.
		$posts_in_cat1 = $this->get_posts_by_category( $cat1 );
		$posts_in_cat2 = $this->get_posts_by_category( $cat2 );
		$posts_in_common_cat = $this->get_posts_by_category( $common_cat );

		$this->assertEquals( count( $posts_in_cat1 ), 1 );
		$this->assertEquals( count( $posts_in_cat2 ), 1 );
		$this->assertEquals( count( $posts_in_common_cat ), 1 );

		// Invoke our method.
		$options = array(
			'old_cat'   => $cat1,
			'new_cat'   => $cat2,
			'overwrite' => false,
		);
		$this->category_metabox->move( $options );

		// Assert that category 1 has no posts.
		$posts_in_cat1 = $this->get_posts_by_category( $cat1 );
		$this->assertEquals( count( $posts_in_cat1 ), 0 );

		// Assert that common category has one posts.
		$posts_in_common_cat = $this->get_posts_by_category( $common_cat );
		$this->assertEquals( count( $posts_in_common_cat ), 1 );

		// Assert that category 2 has two posts.
		$posts_in_cat2 = $this->get_posts_by_category( $cat2 );
		$this->assertEquals( count( $posts_in_cat2 ), 2 );
	}
	
	/**
	 * Test remove category from post
	 */
	public function test_remove_category_from_posts(){
		// Create two categories.
		$cat1 = $this->factory->category->create( array( 'name' => 'cat1' ) );
		$cat2 = $this->factory->category->create( array( 'name' => 'cat2' ) );

		// Create one post in each category.
		$post1 = $this->factory->post->create( array( 'post_title' => 'post1', 'post_category' => array( $cat1 ) ) );
		$post2 = $this->factory->post->create( array( 'post_title' => 'post2', 'post_category' => array( $cat2 ) ) );

		// Assert that each category has one post.
		$posts_in_cat1 = $this->get_posts_by_category( $cat1 );
		$posts_in_cat2 = $this->get_posts_by_category( $cat2 );

		$this->assertEquals( count( $posts_in_cat1 ), 1 );
		$this->assertEquals( count( $posts_in_cat2 ), 1 );

		// call our method.
		$options = array(
			'old_cat'   => $cat1,
			'new_cat'   => -1,
			'overwrite' => true,
		);
		$this->category_metabox->move( $options );

		// Assert that category 1 has no posts.
		$posts_in_cat1 = $this->get_posts_by_category( $cat1 );
		$this->assertEquals( count( $posts_in_cat1 ), 0 );

		// Assert that category 2 has one posts.
		$posts_in_cat2 = $this->get_posts_by_category( $cat2 );
		$this->assertEquals( count( $posts_in_cat2 ), 1 );
	}
	
	/**
	 * Test remove category from post without overwrite.
	 */
	public function test_remove_category_from_posts_without_overwrite() {
		// Create two categories and a common category.
		$cat1 = $this->factory->category->create( array( 'name' => 'cat1' ) );
		$cat2 = $this->factory->category->create( array( 'name' => 'cat2' ) );
		$common_cat = $this->factory->category->create( array( 'name' => 'common_cat' ) );

		// Create one post in each category.
		// The first post will also have the common category.
		$post1 = $this->factory->post->create( array( 'post_title' => 'post1', 'post_category' => array( $cat1, $common_cat ) ) );
		$post2 = $this->factory->post->create( array( 'post_title' => 'post2', 'post_category' => array( $cat2 ) ) );

		// Assert that each category has one post.
		$posts_in_cat1 = $this->get_posts_by_category( $cat1 );
		$posts_in_cat2 = $this->get_posts_by_category( $cat2 );
		$posts_in_common_cat = $this->get_posts_by_category( $common_cat );

		$this->assertEquals( count( $posts_in_cat1 ), 1 );
		$this->assertEquals( count( $posts_in_cat2 ), 1 );
		$this->assertEquals( count( $posts_in_common_cat ), 1 );

		// Invoke our method.
		$options = array(
			'old_cat'   => $cat1,
			'new_cat'   => -1,
			'overwrite' => false,
		);
		$this->category_metabox->move( $options );

		// Assert that category 1 has no posts.
		$posts_in_cat1 = $this->get_posts_by_category( $cat1 );
		$this->assertEquals( count( $posts_in_cat1 ), 0 );

		// Assert that common category has one posts.
		$posts_in_common_cat = $this->get_posts_by_category( $common_cat );
		$this->assertEquals( count( $posts_in_common_cat ), 1 );

		// Assert that category 2 has one posts.
		$posts_in_cat2 = $this->get_posts_by_category( $cat2 );
		$this->assertEquals( count( $posts_in_cat2 ), 1 );
	}
	
	/**
	 * Test remove category from post with overwrite.
	 */
	public function test_remove_category_from_posts_with_overwrite() {
		// Create two categories and a common category.
		$cat1 = $this->factory->category->create( array( 'name' => 'cat1' ) );
		$cat2 = $this->factory->category->create( array( 'name' => 'cat2' ) );
		$common_cat = $this->factory->category->create( array( 'name' => 'common_cat' ) );

		// Create one post in each category.
		// The first post will also have the common category.
		$post1 = $this->factory->post->create( array( 'post_title' => 'post1', 'post_category' => array( $cat1, $common_cat ) ) );
		$post2 = $this->factory->post->create( array( 'post_title' => 'post2', 'post_category' => array( $cat2 ) ) );

		// Assert that each category has one post.
		$posts_in_cat1 = $this->get_posts_by_category( $cat1 );
		$posts_in_cat2 = $this->get_posts_by_category( $cat2 );
		$posts_in_common_cat = $this->get_posts_by_category( $common_cat );

		$this->assertEquals( count( $posts_in_cat1 ), 1 );
		$this->assertEquals( count( $posts_in_cat2 ), 1 );
		$this->assertEquals( count( $posts_in_common_cat ), 1 );

		// Invoke our method.
		$options = array(
			'old_cat'   => $cat1,
			'new_cat'   => -1,
			'overwrite' => true,
		);
		$this->category_metabox->move( $options );

		// Assert that category 1 has no posts.
		$posts_in_cat1 = $this->get_posts_by_category( $cat1 );
		$this->assertEquals( count( $posts_in_cat1 ), 0 );

		// Assert that common category has no posts.
		$posts_in_common_cat = $this->get_posts_by_category( $common_cat );
		$this->assertEquals( count( $posts_in_common_cat ), 0 );

		// Assert that category 2 has one posts.
		$posts_in_cat2 = $this->get_posts_by_category( $cat2 );
		$this->assertEquals( count( $posts_in_cat2 ), 1 );
	}
	
	/**
	 * Test basic case of moving default category.
	 */
	public function test_move_posts_from_default_cat_to_another() {
		// Create one categories and get default category.
		$cat1 = get_option( 'default_category' );
		$cat2 = $this->factory->category->create( array( 'name' => 'cat2' ) );

		// Create one post in each category.
		$post1 = $this->factory->post->create( array( 'post_title' => 'post1', 'post_category' => array( $cat1 ) ) );
		$post2 = $this->factory->post->create( array( 'post_title' => 'post2', 'post_category' => array( $cat2 ) ) );

		// Assert that each category has one post.
		$posts_in_cat1 = $this->get_posts_by_category( $cat1 );
		$posts_in_cat2 = $this->get_posts_by_category( $cat2 );

		$this->assertEquals( count( $posts_in_cat1 ), 1 );
		$this->assertEquals( count( $posts_in_cat2 ), 1 );

		// call our method.
		$options = array(
			'old_cat'   => $cat1,
			'new_cat'   => $cat2,
			'overwrite' => true,
		);
		$this->category_metabox->move( $options );

		// Assert that category 1 (or) default category has no posts.
		$posts_in_cat1 = $this->get_posts_by_category( $cat1 );
		$this->assertEquals( count( $posts_in_cat1 ), 0 );

		// Assert that category 2 has two posts.
		$posts_in_cat2 = $this->get_posts_by_category( $cat2 );
		$this->assertEquals( count( $posts_in_cat2 ), 2 );
	}
	
	/**
	 * Test basic case of moving default category with overwrite.
	 */
	public function test_move_posts_from_default_cat_to_another_with_overwrite() {
		// Create two categories and get default category.
		$cat1 = get_option( 'default_category' );
		$cat2 = $this->factory->category->create( array( 'name' => 'cat2' ) );
		$common_cat = $this->factory->category->create( array( 'name' => 'common_cat' ) );

		// Create one post in each category.
		// The first post will also have the common category.
		$post1 = $this->factory->post->create( array( 'post_title' => 'post1', 'post_category' => array( $cat1, $common_cat ) ) );
		$post2 = $this->factory->post->create( array( 'post_title' => 'post2', 'post_category' => array( $cat2 ) ) );

		// Assert that each category has one post.
		$posts_in_cat1 = $this->get_posts_by_category( $cat1 );
		$posts_in_cat2 = $this->get_posts_by_category( $cat2 );
		$posts_in_common_cat = $this->get_posts_by_category( $common_cat );

		$this->assertEquals( count( $posts_in_cat1 ), 1 );
		$this->assertEquals( count( $posts_in_cat2 ), 1 );
		$this->assertEquals( count( $posts_in_common_cat ), 1 );

		// call our method.
		$options = array(
			'old_cat'   => $cat1,
			'new_cat'   => $cat2,
			'overwrite' => true,
		);
		$this->category_metabox->move( $options );

		// Assert that category 1 (or) default category has no posts.
		$posts_in_cat1 = $this->get_posts_by_category( $cat1 );
		$this->assertEquals( count( $posts_in_cat1 ), 0 );

		// Assert that common category has no posts.
		$posts_in_common_cat = $this->get_posts_by_category( $common_cat );
		$this->assertEquals( count( $posts_in_common_cat ), 0 );

		// Assert that category 2 has two posts.
		$posts_in_cat2 = $this->get_posts_by_category( $cat2 );
		$this->assertEquals( count( $posts_in_cat2 ), 2 );
	}
	
	/**
	 * Test basic case of moving default category without overwrite.
	 */
	public function test_move_posts_from_default_cat_to_another_without_overwrite() {
		// Create two categories and get default category.
		$cat1 = get_option( 'default_category' );
		$cat2 = $this->factory->category->create( array( 'name' => 'cat2' ) );
		$common_cat = $this->factory->category->create( array( 'name' => 'common_cat' ) );

		// Create one post in each category.
		// The first post will also have the common category.
		$post1 = $this->factory->post->create( array( 'post_title' => 'post1', 'post_category' => array( $cat1, $common_cat ) ) );
		$post2 = $this->factory->post->create( array( 'post_title' => 'post2', 'post_category' => array( $cat2 ) ) );

		// Assert that each category has one post.
		$posts_in_cat1 = $this->get_posts_by_category( $cat1 );
		$posts_in_cat2 = $this->get_posts_by_category( $cat2 );
		$posts_in_common_cat = $this->get_posts_by_category( $common_cat );

		$this->assertEquals( count( $posts_in_cat1 ), 1 );
		$this->assertEquals( count( $posts_in_cat2 ), 1 );
		$this->assertEquals( count( $posts_in_common_cat ), 1 );

		// call our method.
		$options = array(
			'old_cat'   => $cat1,
			'new_cat'   => $cat2,
			'overwrite' => false,
		);
		$this->category_metabox->move( $options );

		// Assert that category 1 (or) default category has no posts.
		$posts_in_cat1 = $this->get_posts_by_category( $cat1 );
		$this->assertEquals( count( $posts_in_cat1 ), 0 );

		// Assert that common category has one posts.
		$posts_in_common_cat = $this->get_posts_by_category( $common_cat );
		$this->assertEquals( count( $posts_in_common_cat ), 1 );

		// Assert that category 2 has two posts.
		$posts_in_cat2 = $this->get_posts_by_category( $cat2 );
		$this->assertEquals( count( $posts_in_cat2 ), 2 );
	}
	
	/**
	 * Test remove default category from post
	 */
	public function test_remove_default_category_from_posts(){
		// Get default category.
		$default_cat = get_option( 'default_category' );

		// Create one post.
		$post1 = $this->factory->post->create( array( 'post_title' => 'post1' ) );

		// Assert that default category has one post.
		$posts_in_default_cat = $this->get_posts_by_category( $default_cat );

		$this->assertEquals( count( $posts_in_default_cat ), 1 );

		// call our method.
		$options = array(
			'old_cat'   => $default_cat,
			'new_cat'   => -1,
			'overwrite' => true,
		);
		$this->category_metabox->move( $options );

		// Assert that default category has one post.
		$posts_in_default_cat = $this->get_posts_by_category( $default_cat );
		$this->assertEquals( count( $posts_in_default_cat ), 1 );
	}
}
