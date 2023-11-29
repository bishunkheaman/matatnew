<?php
/**
 * The template for displaying the sapporo stock.
 * Template name: Sapporo Stock
 */
get_header();
while( have_posts() ): the_post();
?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />

<?php

	$page_title = get_the_title();
	echo '<h2>'. $page_title . '</h2>';

 	if( !is_user_logged_in() ){
 		echo '<h2>'.'You Have To Login To View This Page'.'</h2>';
 	}else{ 		
 		global $current_user;
 		$user_role 		= $current_user->roles; 
 		if(in_array('administrator',$user_role) || in_array('shop_manager',$user_role) || in_array('shop_custom',$user_role)):
			wp_get_current_user();
			echo 'User Role : ' . $user_role[0] ."</br>";
			echo 'Username : ' . $current_user->user_login . "</br>";
			echo 'Display Name : ' . $current_user->display_name . "\n";

			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			$stock_product_args = array(
		        'post_type'     	=> 'product',
		        'posts_per_page' 	=> 3, 
		        'paged' 			=> $paged, 
		        'orderby'       	=> 'date',
		        'order'         	=> 'DESC',	      
	        );
	        $stock_product_query = new WP_Query( $stock_product_args );
	        if( $stock_product_query->have_posts()  ):  ?>
		    	<table id="matat-stock-table" class="display">
				    <thead>
				        <tr>
				            <th>Product</th>
				            <th>Parent</th>
				            <th>Units In Stock</th>
				            <th>Stock</th>
				        </tr>
				    </thead>
					<tbody>				       
				        <?php			       	
			       		while( $stock_product_query->have_posts() ){
			                $stock_product_query->the_post();
			                $product = wc_get_product(get_the_ID());
			                if ( $product->is_type( 'variable' ) ) {			                	
			                	$available_variations = $product->get_available_variations();	
			                	foreach ($available_variations as $key => $value):						       
						           	$variation_id 		= $value['variation_id'];	
						           	$vproduct 			= wc_get_product($variation_id);
						           	$product_vari_name 	= $vproduct->get_name();
						           	$stock_quantity 	= $vproduct->get_stock_quantity();
						           	$stock_status 		= $vproduct->get_stock_status();					          
						            ?>
					            	<tr>	            
						            	<td>
						            		<?php 
						            			echo '<span>'. $product_vari_name. '</span>'; 
						            			$attributes  = $vproduct->get_variation_attributes();
						            			echo '<span>'. wc_get_formatted_variation( $vproduct->get_variation_attributes(),true ).'<span>';
						            			//print_r($attributes)
						            		?>
						            	</td>
						            	<td><?php the_title(); ?></td>
						            	<td><?php echo $stock_quantity; ?></td>
						            	<td><?php echo $stock_status ;?></td>
					           		</tr> 
						            <?php	
						        endforeach;             	              
			                }else{
			                	?>
			                	<tr>
								   	<td><?php the_title(); ?></td>	
								   	<td><?php echo '-' ?></td>	
								   	<td>
								   		<?php 
								   		$stock_quantity = $product->get_stock_quantity();
								   		if( $stock_quantity == '' ){
								   			echo '-';
								   		}else{
								   			echo $stock_quantity;
								   		}
								   		?>
								   	</td>		
								   	<td>
							   		   <?php
							   		   	if( $product->is_in_stock() ) {
								   	  			echo 'instock';
								       	  	}else{
								       	  		echo 'out of stock';
								   		}	
							   		   ?>        	 	
								   	</td>			           
								</tr>	
			                	<?php
			                }
			            		               
			       		}			       		
			       		$total_pages = $stock_product_query->max_num_pages;
					    if ($total_pages > 1){
					        $current_page = max(1, get_query_var('paged'));
					        echo '<div class="stock-pagination">';
					        echo paginate_links(array(
					            'base' => get_pagenum_link(1) . '%_%',
					            'format' => '/page/%#%',
					            'current' => $current_page,
					            'total' => $total_pages,
					            'prev_text'    => __('« prev'),
					            'next_text'    => __('next »'),
					        ));
					        echo '</div>';
					    } 
			       		wp_reset_postdata();				           
				       	?>
				    </tbody>
				</table>
			<?php endif; 
	   	else:
	   		echo '<h2>'.'You Are Not Capable To View This Page'.'</h2>';
	   	endif;

 	}

?>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function(){
		let table =new DataTable( '#matat-stock-table', {
		    paging: false,
		    ordering: true,
		    responsive: true    
		} );
	})
</script> 
<?php
endwhile;
get_footer();