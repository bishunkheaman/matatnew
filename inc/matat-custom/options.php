<?php
/**
 * Registering new options page
 */

class Matat_Options_Page{

	function __construct(){
		add_action('admin_menu', array( $this,'admin_menu' ));
	}

	function admin_menu(){
		add_options_page(
			__( 'Matat Options', 'textdomain' ),
			__( 'Matat Option', 'textdomain' ),
			'manage_options',
			'matat_options',
			array(
				$this,
				'settings_page'
			)
		);
	}

	function settings_page(){
		if( isset($_FILES['csv_file'] ) ){			
			$csv = fopen($_FILES['csv_file']['tmp_name'], 'r');
			print_r( $_FILES['csv_file'] );
			$this->matat_advanced_csv_product_import_function($csv);
		}else{
			?>
			<div class="wrap">
		        <div>
		            <h1 style="display: inline-block">Import Products (CSV)</h1>
		            <div class="sample_csv_download_div" style="display: inline-block; margin-left: 60px">
		                <a href="<?php echo get_template_directory_uri(); ?>/assets/sample-product-import.csv" class="button button-secondary" download>Download sample CSV File</a>
		            </div>
		        </div>

		        <div class="import-div">
		            <form method="post" enctype="multipart/form-data">
		                <table class="form-table">
		                    <tbody>
		                    <tr>
		                        <th scope="row"><label for="csv_file">CSV file</label></th>
		                        <td>
		                            <input type="file" name="csv_file" id="csv_file" class="regular-text">
		                            <p class="description">Upload CSV file</p>
		                        </td>
		                    </tr>
		                    </tbody>
		                </table>
						<?php wp_nonce_field( 'matat_advanced_import' ); ?>
						<?php submit_button( 'Import' ); ?>
		            </form>
		        </div>
		    </div>
			<?php
		}
		
	}

	function matat_advanced_csv_product_import_function($csv){
		$products = [];
	    while ($row = fgetcsv($csv)) {
	        $products[] = $row;
	    }
	    fclose($csv);
	    $headers = array_shift($products);
	    $headers = array_map('strtolower', $headers);

	    $products = array_map(function($product) use ($headers){
	        return array_combine($headers, $product);
	    }, $products);

	    echo '<pre>';
	    print_r( $headers );
	    echo '</pre>';

	    echo '<pre>';
	    print_r( $products );
	    echo '</pre>';
	}
}

new Matat_Options_Page();


/**
 * Register a custom menu page.
 */

class Matat_Add_Menu{
	function __construct(){
		add_action( 'admin_menu',array($this,'admin_menu') );		
	}

	function admin_menu(){
		add_menu_page( 
			__( 'Custom Menu Title', 'textdomain' ),
			'Custom Menu',
			'manage_options',
			'custompage',
			array($this,'my_custom_menu_page'),
			plugins_url( 'myplugin/images/icon.png' ),
			6
		); 
	}

	function my_custom_menu_page(){
		esc_html_e( 'Admin Page Test', 'textdomain' );	
	}
}

new Matat_Add_Menu();


/**
 * Adding Sub Menu Page
 */

/**
 * Sub menu class
 *
 * @author Mostafa <mostafa.soufi@hotmail.com>
 */
class Sub_menu {

	/**
	 * Autoload method
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_menu', array(&$this, 'register_sub_menu') );
	}

	/**
	 * Register submenu
	 * @return void
	 */
	public function register_sub_menu() {
		add_submenu_page( 
			'edit.php', 'Sub Menu', 'Sub Menu', 'manage_options', 'submenu-page', array(&$this, 'submenu_page_callback')
		);
	}

	/**
	 * Render submenu
	 * @return void
	 */
	public function submenu_page_callback() {
		echo '<div class="wrap">';
		echo '<h2>Submenu title</h2>';
		echo '</div>';
	}

}

new Sub_menu();
