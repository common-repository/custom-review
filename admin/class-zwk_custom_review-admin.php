<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       zworthkey.com/about-us
 * @since      1.0.0
 *
 * @package    Zwk_custom_review
 * @subpackage Zwk_custom_review/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Zwk_custom_review
 * @subpackage Zwk_custom_review/admin
 * @author     Zworthkey <sales@zworthkey.com>
 */
class Zwk_custom_review_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action( 'admin_menu', array( $this, 'register_zwk_reviews_menu' ) );
		add_action('wp_ajax_zwk_save_review', array($this, 'zwk_save_review') );
		add_action('wp_ajax_nopriv_zwk_save_review', array($this, 'zwk_save_review'));
		add_filter( 'manage_edit-comments_columns', array($this, 'zwk_custom_colmun'),99,1 );
		add_action( 'manage_comments_custom_column' , array($this,'zwk_add_review_image'), 99, 2 );
		add_filter('comment_row_actions', array($this,'zwk_filter_row_comment_action'), 10, 2);
		add_action('wp_ajax_zwk_save_edited_review', array($this, 'zwk_save_edit_review') );
		add_action('wp_ajax_nopriv_zwk_save_edited_review', array($this, 'zwk_save_edit_review'));

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/zwk_custom_review-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style('select1',plugin_dir_url( __FILE__ ) . 'css/select.css');
		wp_enqueue_style('select2','https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css');
		
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Zwk_custom_review_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Zwk_custom_review_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/zwk_custom_review-admin.js', array( 'jquery' ), $this->version, false );		
		wp_enqueue_script('select',plugin_dir_url( __FILE__ ) . 'js/select.js' );
		
	}

	public function register_zwk_reviews_menu(){
		
		
		add_menu_page(
			__('Add new'),// the page title
			__('Custom Review'),//menu title
			'moderate_comment',//capability 
			'zwkmenu',//menu slug/handle this is what you need!!!
			array($this, 'zwkaddreview'),//callback function
			'dashicons-star-filled',//icon_url,
			55//position
		);
		add_submenu_page("zwkmenu","Add Review","Add Review",8,"zwkaddreview",array($this, 'zwkaddreview')); 
		add_submenu_page(null,"Edit Review","Edit Review",8,"zwk_edit_review",array($this,"zwk_edit_review"));
		
	}

	public function zwk_filter_row_comment_action($actions, $comment){
		
		if( 'admin@dashboard.com' === get_comment_author_email($comment->comment_ID) ){
			$actions['edit'] = '<a href="'.esc_url_raw(admin_url("comment.php?page=zwk_edit_review&comment_id=$comment->comment_ID")).'">Edit</a>';
			unset($actions['quickedit']);
		}
		return $actions;
	}

	public function zwk_custom_colmun($columns){
		
		foreach ( $columns as $column_name => $column_info ) {

			$new_columns[ $column_name ] = $column_info;
			
			if('author'=== $column_name){
				$new_columns['zwk_review_image'] = __('Image','');
			}


		}
		return $new_columns;
	}

	public function zwk_add_review_image($colmun, $post_id){
		$image_url = sanitize_text_field( get_comment_meta($post_id,'zwk_image_url', true));
		switch($colmun){
			case 'zwk_review_image':
				if($image_url!=""){
					echo '<img src="'.esc_html($image_url).'" height="100" width="100"/>';
				}
				
			break;
		}
	}

	public function zwk_save_review(){
		
		$comment_id = wp_insert_comment( array(
			'comment_post_ID'      => sanitize_text_field($_POST['productId']), // <=== The product ID where the review will show up
			'comment_author'       => sanitize_text_field($_POST['author_name']),
			'comment_author_email' => 'admin@dashboard.com', // <== Important
			'comment_author_url'   => '',
			'comment_content'      => sanitize_text_field($_POST['reviewText']),
			'comment_type'         => '',
			'comment_parent'       => 0,
			'user_id'              => get_current_user_id(), // <== Important
			'comment_author_IP'    => '',
			'comment_agent'        => '',
			'comment_date'         => sanitize_text_field($_POST['time']),
			'comment_approved'     => 1,
		) );
		
		// HERE inserting the rating (an integer from 1 to 5)
		update_comment_meta( $comment_id, 'rating',sanitize_text_field( $_POST['ratingValue']) );
		update_comment_meta( $comment_id, 'zwk_image_url', sanitize_text_field($_POST['image_url']));
	}


	public function zwkaddreview(){
		global $post, $woocommerce;

		$products = wc_get_products(array(
			'limit'  => -1, // All products
			'status' => 'publish', // Only published products
		) );
		
		
		?>
		<div class="options_group">
		<h1>Add New Review</h1><br/>
			<table><tr>
			<td><label><?php _e( 'Search Products', 'woocommerce' ); ?></label></td>
			<td><select class="js-example-basic-single" name="product" id="zwk_review_product_id" >
				<option disabled selected value >Select a product</option>
			<?php
				foreach($products as $product => $item){
					$prod = wc_get_product($item->id);
					?>
					<option value=<?php echo esc_html($item->id)?>><?php echo esc_html($prod->get_formatted_name());?></option>
					<?php
				}
			?>
			</select></td></tr>
			<tr><td>
			<label>Author name</label></td>
			<td><input type='text' id='zwk_review_aurthor_name' /></td>	
			</tr>
			<tr>
			<td><label>Rating<label></td>
			<td>
			<section class='rating-widget'>
  
				<!-- Rating Stars Box -->
				<div class='rating-stars text-center'>
					<ul id='stars'>
					<li class='star' data-value='1'>
						<i class='fa fa-star fa-fw'></i>
					</li>
					<li class='star' data-value='2'>
						<i class='fa fa-star fa-fw'></i>
					</li>
					<li class='star' data-value='3'>
						<i class='fa fa-star fa-fw'></i>
					</li>
					<li class='star' data-value='4'>
						<i class='fa fa-star fa-fw'></i>
					</li>
					<li class='star' data-value='5'>
						<i class='fa fa-star fa-fw'></i>
					</li>
					</ul>
				</div>

				</section>
			</td>
			<tr>
				<td><label>Select Time</label></td>
				<td><input type="datetime-local" id="zwk_review_date"></td>
			</tr>
			</tr>
			<tr><td>
			<label>Enter Text</label></td>
			<td><textarea id='zwk_review_text' placeholder='review text' rows="4" cols="50"></textarea></td></tr>
			<tr><td>
			<label>Upload Image</label></td>

		<?php
		if(function_exists( 'wp_enqueue_media' )){
			wp_enqueue_media();
		}else{
			wp_enqueue_style('thickbox');
			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
		}
		?>
		<p><td>
			<input id="zwk_review_image_url" type="hidden" name="header_logo" size="20px" value="<?php echo esc_html(get_option('zwk_review_image')) ?>" style="display:none">
			<img id="zwk_review_image" src="<?php echo esc_html(get_option('zwk_review_image')) ?>" height="100" width="100" style="display:none;"/>
			<a href="#" id="zwk_review_image_upload">Upload</a></td></tr>
		</p>    
		
		</table>
		</div>
		<br/><a class='button' id="zwk_review_submit">Submit</a>
		<?php
	}

	public function zwk_edit_review(){
		global $post, $woocommerce;
		$comment_id = sanitize_text_field($_REQUEST['comment_id']);
		$comment = sanitize_text_field(get_comment( $comment_id ));
		$author = $comment->comment_author;
		$text	= $comment->comment_content;
		$rating = get_comment_meta($comment_id,'rating',true);
		$image_url = get_comment_meta($comment_id,'zwk_image_url', true);
		$time 	= str_replace(' ','T',get_comment_date("Y-m-d h:i", $comment_id));
		$product_id = $comment->comment_post_ID;
		$products = wc_get_products(array(
			'limit'  => -1, // All products
			'status' => 'publish', // Only published products
		) );
		
		?>
		
		<div class="options_group">
		<h1>Edit Review</h1><br/>
			<table><tr>
			<td><label><?php _e( 'Search Products', 'woocommerce' ); ?></label></td>
			<td><select class="js-example-basic-single" name="product" id="zwk_edit_review_product_id" >
				<option disabled selected value >Select a product</option>
			<?php
				foreach($products as $product => $item){
					$prod = wc_get_product($item->id);
					?>
					<option value=<?php echo esc_html($item->id);?> <?php selected($product_id,$item->id)?>><?php echo esc_html($prod->get_formatted_name());?></option>
					<?php
				}
			?>
			</select>
			
			
			</td></tr>
			<tr><td>
			<label>Author name</label></td>
			<td><input type='text' id='zwk_edit_review_aurthor_name' value='<?php echo esc_html($author)?>' /></td>	
			</tr>
			<tr>
			<td><label>Rating<label></td>
			<td>
			<section class='rating-widget'>
  
				<!-- Rating Stars Box -->
				<div class='rating-stars text-center'>
					<ul id='zwk_edit_review_stars'>
					<li class='star' data-value='1'>
						<i class='fa fa-star fa-fw'></i>
					</li>
					<li class='star' data-value='2'>
						<i class='fa fa-star fa-fw'></i>
					</li>
					<li class='star' data-value='3'>
						<i class='fa fa-star fa-fw'></i>
					</li>
					<li class='star' data-value='4'>
						<i class='fa fa-star fa-fw'></i>
					</li>
					<li class='star' data-value='5'>
						<i class='fa fa-star fa-fw'></i>
					</li>
					</ul>
				</div>

				</section>
			</td>
			<script>
				jQuery(document).ready(function(){
					jQuery('#zwk_edit_review_stars li').each(function(){
						var rating = <?php echo esc_html($rating);?>;
						if(jQuery(this).attr('data-value')>rating){
							jQuery(this).removeClass('selected');
						}
					})
				})
				
			</script>
			<tr>
				<td><label>Select Time</label></td>
				<td><input type="datetime-local" id="zwk_edit_review_date" value="<?php echo esc_html($time);?>"></td>
			</tr>
			</tr>
			<tr><td>
			<label>Enter Text</label></td>
			<td><textarea id='zwk_edit_review_text' placeholder='review text' rows="4" cols="50" ><?php echo esc_html($text);?></textarea></td></tr>
			<tr><td>
			<label>Upload Image</label></td>

		<?php
		
		if(function_exists( 'wp_enqueue_media' )){
			wp_enqueue_media();
		}else{
			wp_enqueue_style('thickbox');
			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
		}
		?>
		<p><td>
			<input id="zwk_edit_review_image_url" type="hidden" name="header_logo" size="20px" value="<?php echo esc_html(get_option('zwk_edit_review_image')); ?>" style="display:none">
			<img id="zwk_edit_review_image" src="<?php echo esc_html(get_option('zwk_eidt_review_image')); ?>" height="100" width="100" />
			<a href="#" id="zwk_edit_review_image_upload">Upload</a></td></tr>
		</p>    
		
		</table>
		</div>
		<br/><a class='button' id="zwk_edit_review_submit" comment_id = <?php echo esc_html($comment_id);?>>Save</a>
		<script>
			(function($){
				$(document).ready(function(){
					var url = '<?php echo esc_html($image_url)?>';
					if(url==''){
						$('#zwk_edit_review_image').hide();
					}else{
						$('#zwk_edit_review_image').attr('src',url);
						$('#zwk_edit_review_image_url').val(url);
						$('#zwk_edit_review_image').after('<br/>');
					}
				})
			})(jQuery);
		</script>
		<?php
	}

	public function zwk_save_edit_review(){
		$comment['comment_post_ID'] = sanitize_text_field($_POST['productId']);
		$comment['comment_ID'] = sanitize_text_field($_POST['commentId']);
		$comment['comment_author']= sanitize_text_field($_POST['author_name']);
		$comment['comment_content']= sanitize_text_field($_POST['reviewText']);
		$comment['comment_date']= sanitize_text_field($_POST['time']);

		wp_update_comment($comment);
		update_comment_meta( sanitize_text_field($_POST['commentId']), 'rating', sanitize_text_field($_POST['ratingValue']) );
		update_comment_meta( sanitize_text_field($_POST['commentId']), 'zwk_image_url', sanitize_text_field($_POST['image_url']));

	
	}


	

}
