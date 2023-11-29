<?php


/**
 * 
 */
add_action( 'woocommerce_admin_order_data_after_order_details', 'matat_editable_order_meta_general' );

function matat_editable_order_meta_general( $order ){

    ?>
        <br class="clear" />
        <h3>Gift Order <a href="#" class="edit_address">Edit</a></h3>
        <?php
            /*
             * get all the meta data values we need
             */

            $is_gift = get_post_meta($order->get_id(), 'is_gift', true);
            $gift_wrap = get_post_meta($order->get_id(), 'gift_wrap', true);
            $gift_name =    get_post_meta($order->get_id(), 'gift_name', true);
            $gift_message = get_post_meta($order->get_id(), 'gift_message', true);
        ?>
        <div class="address">
            <p><strong>Is this a gift order?</strong><?php echo $is_gift ? 'Yes' : 'No' ?></p>
            <?php
                // we show the rest fields in this column only if this order is marked as a gift
                if( $is_gift ) :
                ?>
                    <p><strong>Gift Wrap:</strong> <?php echo esc_html( $gift_wrap ) ?></p>
                    <p><strong>Recipient name:</strong> <?php echo esc_html( $gift_name ) ?></p>
                    <p><strong>Gift message:</strong> <?php echo wpautop( esc_html( $gift_message ) ) ?></p>
                <?php
                endif;
            ?>
        </div>
        <div class="edit_address">
            <?php

                woocommerce_wp_radio( array(
                    'id' => 'is_gift',
                    'name' => 'is_gift',
                    'label' => 'Is this a gift order?',
                    'value' => $is_gift,
                    'options' => array(
                        '' => 'No',
                        '1' => 'Yes'
                    ),
                    'style' => 'width:16px', // required for checkboxes and radio buttons
                    'wrapper_class' => 'form-field-wide' // always add this class
                ) );

                woocommerce_wp_select( array(
                    'id' => 'gift_wrap',
                    'label' => 'Gift Wrap:',
                    'name' => 'gift_wrap',
                    'value' => $gift_wrap,
                    'options' => array(
                        '' => 'No Wrap',
                        'Basic Wrap' => 'Basic Wrap',
                        'Magic Wrap' => 'Magic Wrap'
                    ),
                    'wrapper_class' => 'form-field-wide'
                ) );

                woocommerce_wp_text_input( array(
                    'id' => 'gift_name',
                    'label' => 'Recipient name:',
                    'name' => 'gift_name',
                    'value' => $gift_name,
                    'wrapper_class' => 'form-field-wide'
                ) );

                woocommerce_wp_textarea_input( array(
                    'id' => 'gift_message',
                    'name' => 'gift_message',
                    'label' => 'Gift message:',
                    'value' => $gift_message,
                    'wrapper_class' => 'form-field-wide'
                ) );

            ?>
        </div>
    <?php 
}

add_action( 'woocommerce_admin_order_data_after_billing_address', 'matat_editable_order_meta_billing' );

function matat_editable_order_meta_billing( $order ){
    
    $contactmethod = get_post_meta($order->get_id(), 'contactmethod', true);
    $billingText = get_post_meta($order->get_id(), 'billingText', true);
    ?>
        <div class="address">
            <p<?php if( ! $contactmethod ) { echo ' class="none_set"'; } ?>>
                <strong>Preferred Contact Method:</strong>
                <?php echo $contactmethod ? esc_html( $contactmethod ) : 'No contact method selected.' ?>
            </p>

            <p<?php if( ! $billingText ) { echo ' class="none_set"'; } ?>>
                <strong>Billing Text:</strong>
                <?php echo $billingText ? esc_html( $billingText ) : 'No contact method selected.' ?>
            </p>
        </div>
        <div class="edit_address">
            <?php
                woocommerce_wp_text_input( array(
                    'id' => 'billingText',
                    'label' => 'Billing Text',
                    'wrapper_class' => 'form-field-wide',
                    'value' => $billingText ,
                    'description' => 'Billing Text',                    
                ) );

                woocommerce_wp_select( array(
                    'id' => 'contactmethod',
                    'label' => 'Preferred Contact Method',
                    'wrapper_class' => 'form-field-wide',
                    'value' => $contactmethod,
                    'description' => 'Please, contact the customer only with the method selected here.',
                    'options' => array(
                        'By Phone' => 'By Phone', // option value == option name
                        'By Email' => 'By Email'
                    )
                ) );
            ?>
        </div>
    <?php
}

add_action( 'woocommerce_admin_order_data_after_shipping_address', 'matat_editable_order_meta_shipping' );

function matat_editable_order_meta_shipping( $order ){

    $shippingdate = get_post_meta($order->get_id(), 'shippingdate', true);

    ?>
        <div class="address">
            <p<?php if( empty( $shippingdate ) ) { echo ' class="none_set"'; } ?>>
                <strong>Shipping date:</strong>
                <?php echo ! empty( $shippingdate ) ? $shippingdate : 'Anytime.' ?>
            </p>
        </div>
        <div class="edit_address">
            <?php
                woocommerce_wp_text_input( array(
                    'id' => 'shippingdate',
                    'label' => 'Shipping date',
                    'wrapper_class' => 'form-field-wide',
                    'class' => 'date-picker',
                    'style' => 'width:100%',
                    'value' => $shippingdate,
                    'description' => 'This is the day, when the customer would like to receive his order.'
                ) );
            ?>
        </div>
    <?php
}

add_action( 'woocommerce_process_shop_order_meta', 'matat_save_general_details' );

function matat_save_general_details( $order_id ){
    $is_gift        = sanitize_text_field($_POST['is_gift']);
    $gift_wrap      = sanitize_text_field($_POST['gift_wrap']);
    $gift_name      = sanitize_text_field($_POST['gift_name']);
    $gift_message   = sanitize_text_field($_POST['gift_message']);
    $billingText    = sanitize_text_field($_POST['billingText']);
    $contactmethod  = sanitize_text_field($_POST['contactmethod']);
    $shippingdate   = sanitize_text_field($_POST['shippingdate']);

    update_post_meta( $order_id, 'is_gift', $is_gift );
    update_post_meta( $order_id, 'gift_wrap', $gift_wrap );
    update_post_meta( $order_id, 'gift_name', $gift_name );
    update_post_meta( $order_id, 'gift_message', $gift_message );
    update_post_meta( $order_id, 'billingText', $billingText );
    update_post_meta( $order_id, 'contactmethod', $contactmethod );
    update_post_meta( $order_id, 'shippingdate', $shippingdate );
    // wc_clean() and wc_sanitize_textarea() are WooCommerce sanitization functions
    
}
/**
 * 
 */

function custom_my_account_content() {
    get_current_user_id();
    $current_page    = empty( $current_page ) ? 1 : absint( $current_page );
        $customer_orders = wc_get_orders(
            apply_filters(
                'woocommerce_my_account_my_orders_query',
                array(
                    'customer' => get_current_user_id(),
                    'page'     => $current_page,
                    'paginate' => true,
                )
            )
        );

        //print_r($customer_orders);
        //echo get_user_locale();
        $maxtime = null;
        foreach ( $customer_orders->orders as $customer_order ) {
           // echo $customer_order;
            // echo '<pre>';
            // print_r($customer_order);
            // echo '</pre>';

            $order      = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
            echo $modified_date  = strtotime($order->data['date_modified']);
            // echo '</br>';
             if( $modified_date > $maxtime  ){
                $maxtime = $modified_date;
             }

            $item_count = $order->get_item_count() - $order->get_item_count_refunded();
    
            echo $is_gift = get_post_meta($order->get_id(), 'is_gift', true);
            echo $gift_wrap = get_post_meta($order->get_id(), 'gift_wrap', true);
        }
       

}
add_action('woocommerce_after_edit_account_form', 'custom_my_account_content');


