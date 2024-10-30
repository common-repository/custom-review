<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       zworthkey.com/about-us
 * @since      1.0.0
 *
 * @package    Zwk_custom_review
 * @subpackage Zwk_custom_review/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Zwk_custom_review
 * @subpackage Zwk_custom_review/public
 * @author     Zworthkey <sales@zworthkey.com>
 */
class Zwk_custom_review_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		
		add_action( 'woocommerce_review_before', array($this,'zwk_comment_view') );
		add_action( 'wp_enqueue_scripts', array($this,'enqueue_load_fa'));	

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

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
		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/zwk_custom_review-public.css', array(), $this->version, 'all' );
		
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/zwk_custom_review-public.js', array( 'jquery' ), $this->version, false );		

		
	}


	public function enqueue_load_fa() {
		wp_enqueue_style( 'load-fa', 'https://use.fontawesome.com/releases/v5.5.0/css/all.css' );
	  }

	public function zwk_comment_view($comment){
		
		if( 'admin@dashboard.com' === $comment->comment_author_email){
			$id = $comment->comment_ID;
			$image_url = sanitize_text_field(get_comment_meta($comment->comment_ID,'zwk_image_url', true));
			?>
	
				<script>
					(function($){
						$(document).ready(function(){
							var icon =  $('#comment-<?php echo esc_html($id);?>').find('.meta');
							icon.append("<i class='fas fa-user-check' title='Verified Customer' style='font-size:18px;color:green'></i>");
						})
					})(jQuery);
				</script>
			<?php
			if($image_url!=''){
				add_thickbox();
				?>
				<div id="my-content-id" style="display:none;">
					<img src="<?php echo esc_html($image_url);?>">
				</div>
				<script>
					// alert('this');
					(function($){
						$(document).ready(function(){
							var comment = $('#comment-<?php echo esc_html($id);?>');
							var img = comment.children('img')
							img.attr('src','<?php echo esc_html($image_url);?>');
							img.wrap('<a href="TB_inline?width=60%&height=40%&inlineId=my-content-id" class="thickbox" title="Image Preview"></a>');
							
						})
					})(jQuery);
				</script>
				
			<?php
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/zwk_custom_review-public.css', array(), $this->version, 'all' );

			}
		}
			
	}

	
}
