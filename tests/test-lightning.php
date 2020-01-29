<?php

/*
cd /app
bash setup-phpunit.sh
source ~/.bashrc
cd $(wp theme path --dir lightning)
phpunit
*/

class LightningTest extends WP_UnitTestCase {

	public static function lightning_is_mobile_true() {
		return true;
	}

	function test_lightning_get_theme_options() {
		print PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		print 'test_lightning_get_theme_options' . PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		$test_array = array(
			// array(
			// 'options'   => array(), // フィールド自体が存在しない場合にサンプル画像を返す
			// 'check_key' => 'top_slide_image_1',
			// 'correct'   => get_template_directory_uri() . '/assets/images/top_image_1.jpg',
			// ),
			array(
				'options'   => array(
					'top_slide_image_1' => null,
				),
				'check_key' => 'top_slide_image_1',
				'correct'   => '',
			),
			array(
				'options'   => array(
					'top_slide_image_1' => '',
				),
				'check_key' => 'top_slide_image_1',
				'correct'   => '',
			),
			// array(
			// 'options'   => array(
			// 'top_slide_image_1' => 'http://aaa.com/sample.jpg',
			// ),
			// 'check_key' => 'top_slide_image_1',
			// 'correct'   => 'http://aaa.com/sample.jpg',
			// ),
			// array(
			// 'options'   => array(),
			// 'check_key' => 'top_slide_text_title_1',
			// 'correct'   => __( 'Simple and Customize easy <br>WordPress theme.', 'lightning' ),
			// ),
			// array(
			// 'options'   => array(
			// 'top_slide_text_title_1' => null,
			// ),
			// 'check_key' => 'top_slide_text_title_1',
			// 'correct'   => '',
			// ),
			// array(
			// 'options'   => array(
			// 'top_slide_text_title_1' => '',
			// ),
			// 'check_key' => 'top_slide_text_title_1',
			// 'correct'   => '',
			// ),
		);
		// 操作前のオプション値を取得
		$before_options = get_option( 'lightning_theme_options' );
		foreach ( $test_array as $key => $value ) {
			delete_option( 'lightning_theme_options' );
			$lightning_theme_options = $value['options'];
			add_option( 'lightning_theme_options', $lightning_theme_options );

			$result    = lightning_get_theme_options();
			$check_key = $value['check_key'];
			print 'return  :' . $result[ $check_key ] . PHP_EOL;
			print 'correct :' . $value['correct'] . PHP_EOL;
			$this->assertEquals( $value['correct'], $result[ $check_key ] );

		}
		// テストで入れたオプションを削除
		delete_option( 'lightning_theme_options' );
		if ( $before_options ) {
			// テスト前のオプション値に戻す
			add_option( 'lightning_theme_options', $before_options );
		}
	}

	function test_lightning_top_slide_count() {
		$test_array = array(
			// array(
			// 'options' => array(),
			// 'correct' => 3,
			// ),
			array(
				'options' => array(
					'top_slide_image_1' => 'https://lightning.nagoya/images/sample.jpg',
					'top_slide_image_2' => 'https://lightning.nagoya/images/sample.jpg',
				),
				'correct' => 2,
			),
			array(
				'options' => array(
					'top_slide_image_1' => 'https://lightning.nagoya/images/sample.jpg',
					'top_slide_image_5' => 'https://lightning.nagoya/images/sample.jpg',
				),
				'correct' => 2,
			),
			array(
				'options' => array(
					'top_slide_image_1' => 'https://lightning.nagoya/images/sample.jpg',
					'top_slide_image_2' => '',
					'top_slide_image_5' => 'https://lightning.nagoya/images/sample.jpg',
				),
				'correct' => 2,
			),
		);
		foreach ( $test_array as $key => $value ) {
			delete_option( 'lightning_theme_options' );
			$lightning_theme_options = $value['options'];
			add_option( 'lightning_theme_options', $lightning_theme_options );
			$lightning_theme_options = get_option( 'lightning_theme_options' );
			$result                  = lightning_top_slide_count( $lightning_theme_options );

			$this->assertEquals( $value['correct'], $result );
		}
	}


	function test_lightning_slide_cover_style() {
		$test_array = array(
			array(
				'options' => array(
					'top_slide_cover_color_1'   => '#ff0000',
					'top_slide_cover_opacity_1' => '80',
				),
				'correct' => 'background-color:#ff0000;opacity:0.8',
			),
			array(
				'options' => array(
					'top_slide_cover_color_1'   => '#ff0000',
					'top_slide_cover_opacity_1' => null,
				),
				'correct' => '',
			),
			array(
				'options' => array(
					'top_slide_cover_color_1'   => '#ff0000',
					'top_slide_cover_opacity_1' => 0,
				),
				'correct' => '',
			),
			array(
				'options' => array(
					'top_slide_cover_color_1'   => '',
					'top_slide_cover_opacity_1' => 50,
				),
				'correct' => '',
			),
			array(
				'options' => array(
					'top_slide_cover_color_1'   => null,
					'top_slide_cover_opacity_1' => 50,
				),
				'correct' => '',
			),
		);

		foreach ( $test_array as $key => $value ) {
			$lightning_theme_options = $value['options'];
			$result                  = lightning_slide_cover_style( $lightning_theme_options, 1 );
			$this->assertEquals( $value['correct'], $result );
		}
	}


	/*
	モバイル画像の表示仕様が変更になったので現在使用していない
	 */
	function test_lightninig_top_slide_image() {
		$lightning_theme_options                             = get_option( 'lightning_theme_options' );
		$lightning_theme_options['top_slide_image_1']        = 'https://lightning.nagoya/images/sample.jpg';
		$lightning_theme_options['top_slide_image_mobile_1'] = 'https://lightning.nagoya/images/sample_mobile.jpg';
		$lightning_theme_options['top_slide_image_2']        = '';
		$lightning_theme_options['top_slide_image_mobile_2'] = 'https://lightning.nagoya/images/sample_mobile.jpg';
		$lightning_theme_options['top_slide_image_3']        = 'https://lightning.nagoya/images/sample.jpg';
		update_option( 'lightning_theme_options', $lightning_theme_options );

		// ユーザーエージェントがとれない時は is_mobileはfalseを返す
		$is_mobile_state = lightning_is_mobile();
		$this->assertEquals( false, $is_mobile_state );

		// モバイル時 //////////////////////////////////////////////////////////////////////////////

		// モバイルのフィルターフックが動作するかどうか
		add_filter( 'lightning_is_mobile', array( __CLASS__, 'lightning_is_mobile_true' ), 10, 2 );
		$is_mobile_state = lightning_is_mobile();
		$this->assertEquals( true, $is_mobile_state );

	}


	function test_sanitaize_number() {
		$test_array = array(
			array(
				'input'   => '１０',
				'correct' => 10,
			),
			array(
				'input'   => 'test',
				'correct' => 0,
			),
			array(
				'input'   => '',
				'correct' => 0,
			),
		);

		foreach ( $test_array as $key => $value ) {
			$return = lightning_sanitize_number( $value['input'] );
			$this->assertEquals( $value['correct'], $return );
		}
	}

	function test_lightning_sanitize_number_percentage() {
		$test_array = array(
			array(
				'input'   => '100',
				'correct' => 100,
			),
			array(
				'input'   => '0',
				'correct' => 0,
			),
			array(
				'input'   => '10000',
				'correct' => 0,
			),
			array(
				'input'   => '',
				'correct' => 0,
			),
		);

		foreach ( $test_array as $key => $value ) {
			$return = lightning_sanitize_number_percentage( $value['input'] );
			$this->assertEquals( $value['correct'], $return );
		}
	}

	function test_lightning_is_frontpage_onecolumn() {
		$before_options = get_option( 'lightning_theme_options' );
		// トップに指定されてる固定ページIDを取得
		$before_page_on_front = get_option( 'page_on_front' );
		if ( $before_page_on_front ) {
			$page_on_front = $before_page_on_front;
		} else {
			$page_on_front = 1;
		}
		// トップに指定されてる固定ページのテンプレートを取得
		$before_template = get_post_meta( $page_on_front, '_wp_page_template', true );

		$test_array = array(
			// カスタマイザーでチェックが入っている場合（優先）
			array(
				'top_sidebar_hidden' => true,
				'_wp_page_template'  => 'default',
				'correct'            => true,
			),
			// カスタマイザーでチェックが入っていなくても固定ページで指定がある場合
			array(
				'top_sidebar_hidden' => false,
				'_wp_page_template'  => 'page-onecolumn.php',
				'correct'            => true,
			),

		);

		print PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		print 'is_frontpage_onecolumn' . PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		foreach ( $test_array as $key => $value ) {

			// カスタマイザーでの指定
			$options['top_sidebar_hidden'] = $value['top_sidebar_hidden'];
			update_option( 'lightning_theme_options', $options );

			// 固定ページ側のテンプレート
			update_option( 'page_on_front', $page_on_front );
			update_post_meta( $page_on_front, '_wp_page_template', $value['_wp_page_template'] );

			$return = lightning_is_frontpage_onecolumn();
			print 'return  :' . $return . PHP_EOL;
			print 'correct :' . $value['correct'] . PHP_EOL;
			$this->assertEquals( $value['correct'], $return );
		}

		/*
		 テスト前の値に戻す
		/*--------------------------------*/
		update_option( 'lightning_theme_options', $before_options );
		update_option( 'page_on_front', $before_page_on_front );
		update_post_meta( $before_page_on_front, '_wp_page_template', $before_template );
	}


	function test_lightning_is_layout_onecolumn() {
		print PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		print 'lightning_is_layout_onecolumn' . PHP_EOL;
		print '------------------------------------' . PHP_EOL;

		$before_option         = get_option( 'lightning_theme_options' );
		$before_page_for_posts = get_option( 'page_for_posts' ); // 投稿トップに指定するページ
		$before_page_on_front  = get_option( 'page_on_front' ); // フロントに指定する固定ページ
		$before_show_on_front  = get_option( 'show_on_front' ); // トップページ指定するかどうか page or posts

		// Create test category
		$catarr  = array(
			'cat_name' => 'test_category',
		);
		$cate_id = wp_insert_category( $catarr );

		// Create test post
		$post    = array(
			'post_title'    => 'test',
			'post_status'   => 'publish',
			'post_content'  => 'content',
			'post_category' => array( $cate_id ),
		);
		$post_id = wp_insert_post( $post );

		// Create test home page
		$post         = array(
			'post_title'   => 'post_top',
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_content' => 'content',
		);
		$home_page_id = wp_insert_post( $post );

		// Create test home page
		$post          = array(
			'post_title'   => 'front_page',
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_content' => 'content',
		);
		$front_page_id = wp_insert_post( $post );
		update_option( 'page_on_front', $front_page_id ); // フロントに指定する固定ページ
		update_option( 'page_for_posts', $home_page_id ); // 投稿トップに指定する固定ページ
		update_option( 'show_on_front', 'page' ); // or posts

		/*
		 Test Array
		/*--------------------------------*/
		$test_array = array(
			// Front page
			array(
				'options'     => array(
					'layout' => array(
						'front-page' => 'col-one',
					),
				),
				'post_custom' => '',
				'target_url'  => home_url( '/' ),
				'correct'     => true,
			),
			// Front page _ old one column setting
			// array(
			// 	'options'     => array(
			// 		'top_sidebar_hidden' => true,
			// 	),
			// 	'post_custom' => '',
			// 	'target_url'  => home_url( '/' ),
			// 	'correct'     => true,
			// ),
			// Front page _ old one column setting
			// トップ１カラム指定が古い状態で万が一残ってたとしても新しい設定が2カラムなら2カラムにする
			// array(
			// 	'options'     => array(
			// 		'top_sidebar_hidden' => true,
			// 		'layout' => array(
			// 			'front-page' => 'col-two',
			// 		),
			// 	),
			// 	'post_custom' => '',
			// 	'target_url'  => home_url( '/' ),
			// 	'correct'     => false,
			// ),
			// Search
			array(
				'options'     => array(
					'layout' => array(
						'search' => 'col-one',
					),
				),
				'post_custom' => '',
				'target_url'  => home_url( '/' ) . '?s=aaa',
				'correct'     => true,
			),
			// 404
			array(
				'options'     => array(
					'layout' => array(
						'error404' => 'col-one',
					),
				),
				'post_custom' => '',
				'target_url'  => home_url( '/' ) . '?name=abcdefg',
				'correct'     => true,
			),
			// Category
			array(
				'options'     => array(
					'layout' => array(
						'archive' => 'col-one',
					),
				),
				'post_custom' => '',
				'target_url'  => get_term_link( $cate_id ),
				'correct'     => true,
			),
			// Post home
			array(
				'page_type'   => 'home',
				'options'     => array(
					'layout' => array(
						'archive' => 'col-one',
					),
				),
				'post_custom' => '',
				'target_url'  => get_permalink( get_option( 'page_for_posts' ) ),
				'correct'     => true,
			),
			// Single
			array(
				'page_type'   => 'single',
				'options'     => array(
					'layout' => array(
						'single' => 'col-one',
					),
				),
				'post_custom' => '',
				'target_url'  => get_permalink( $post_id ),
				'correct'     => true,
			),
		);

		foreach ( $test_array as $value ) {
			$options = $value['options'];
			update_option( 'lightning_theme_options', $options );

			// Move to test page
			$this->go_to( $value['target_url'] );

			$return = lightning_is_layout_onecolumn();
			print 'url     :' . $_SERVER['REQUEST_URI'] . PHP_EOL;
			print 'return  :' . $return . PHP_EOL;
			print 'correct :' . $value['correct'] . PHP_EOL;
			$this->assertEquals( $value['correct'], $return );
		}

		/*
		 テスト前の値に戻す
		/*--------------------------------*/
		wp_delete_post( $post_id );
		wp_delete_post( $home_page_id );
		$cate_id = wp_delete_category( $catarr );
		update_option( 'lightning_theme_options', $before_option );
		update_option( 'page_for_posts', $before_page_for_posts );
		update_option( 'page_on_front', $before_page_on_front );
		update_option( 'show_on_front', $before_show_on_front );
	}

	function test_lightning_check_color_mode() {
		$test_array = array(
			array(
				'input'   => '#fff',
				'correct' => 'light',
			),
			array(
				'input'   => '#ffffff',
				'correct' => 'light',
			),
			array(
				'input'   => '#000',
				'correct' => 'dark',
			),
			array(
				'input'   => '#f00',
				'correct' => 'dark',
			),
			array(
				'input'   => '#ff0',
				'correct' => 'light',
			),
			array(
				'input'   => '#0ff',
				'correct' => 'light',
			),
			array(
				'input'   => '#808080',
				'correct' => 'light',
			),
			array(
				'input'   => '#7f7f7f',
				'correct' => 'dark',
			),
		);
		print PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		print 'test_lightning_check_color_mode' . PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		foreach ( $test_array as $key => $value ) {
			$return = lightning_check_color_mode( $value['input'] );
			print 'return  :' . $return . PHP_EOL;
			print 'correct :' . $value['correct'] . PHP_EOL;
			$this->assertEquals( $value['correct'], $return );
		}
	}

	function test_lightning_is_slide_outer_link() {
		$test_array = array(
			array(
				'options' => array(
					'top_slide_url_1'      => 'https://google.com',
					'top_slide_text_btn_1' => '詳しくはこちら',
				),
				'correct' => false,
			),
			array(
				'options' => array(
					'top_slide_url_1'      => '',
					'top_slide_text_btn_1' => '詳しくはこちら',
				),
				'correct' => false,
			),
			array(
				'options' => array(
					'top_slide_url_1'      => 'https://google.com',
					'top_slide_text_btn_1' => '',
				),
				'correct' => true,
			),
		);
		print PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		print 'test_lightning_is_slide_outer_link' . PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		foreach ( $test_array as $key => $value ) {
			$return = lightning_is_slide_outer_link( $value['options'], 1 );
			print 'return  :' . $return . PHP_EOL;
			print 'correct :' . $value['correct'] . PHP_EOL;
			$this->assertEquals( $value['correct'], $return );
		}
	}

}
