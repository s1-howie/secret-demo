<?php
/**
 * Plugin Name: Custom Mailchimp for Wp
 * Description: Plugin Extended.
 * Version: 1.0.0
 * Author: Famcom
 * Author URI: https://famcom.com
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

//if (is_plugin_active('mailchimp-for-wp/mailchimp-for-wp.php')) {

    function custom_contact_form() {
        if ( isset( $_POST['submit_form'] ) ) {      
          
           require_once ABSPATH . WPINC . '/pluggable.php';
           require_once(ABSPATH . 'wp-content/plugins/mailchimp-for-wp/mailchimp-for-wp.php');
           
           //require_once get_template_directory() . '/mailchimpsettings/bspblog-config.php';
           
           $aname="BSP Research";
           $name = sanitize_text_field( $_POST['fname'] );
           $email = sanitize_email( $_POST['email'] );
           $message = esc_textarea( $_POST['message'] );
           $organization = sanitize_text_field( $_POST['organization'] );
           $is_signup = isset($_POST['signup'])?'Yes':'No';       
           $subject = 'Contact Form Submission';
           $to = get_option('admin_email');
           $headers = array(
               'From: ' . $aname . ' <' . $email . '>',
               'Content-Type: text/html; charset=UTF-8',
           );
           $all_message ='<p>Find details of a new contactus submission</p>';
           $all_message .='<p>Name:'.$name.'</p>';
           $all_message .='<p>Organization:'.$organization.'</p>';
           $all_message .='<p>Email:'.$email.'</p>';
           $all_message .='<p>Message:'.$message.'</p>';
           
           if($is_signup=='Yes')
           {
             
            
               @add_or_update_member('1407967842',$email,$name);
              
           }
          
           //save in db
           global $wpdb;        
           $table_name = $wpdb->prefix . 'contactus';
    
           $data = array(
               'fullname' => $name,
               'email' => $email,
               'message' => $message,
               'organization' => $organization,
               'is_signup' => $is_signup      
           );
           //mail to user
           $user_subject = 'Verify your email';
           $user_to = $email;
           $admin_name='BSP Research';
           $user_headers = array(
               'From: ' . $admin_name . ' <' . $email . '>',
               'Content-Type: text/html; charset=UTF-8',
           );
           $code=base64_encode($email);
           $user_link='<a href="https://bsp.thefamcomlab.com/verify-email?c='.$code.'">Verify Email</a>';
           $user_message='<p>Please click the link to verify your email address'.$user_link.'</p>';
       if(!empty($name) && !empty($email) && !empty($message)){
           $wpdb->insert( $table_name, $data );
           //wp_mail('dipti@famcominc.com', 'Test Email', 'This is a test email from WordPress.');
          
          
           wp_mail( $to, $subject, $all_message, $headers );  
           wp_mail( $user_to, $user_subject, $user_message, $user_headers );      
           $msg="Thank you for your inquiry! We will get back to you within 48 hours.We've sent you a confirmation email, please click the link to verify your address.";
           
           
       }
       else{
           $error="Please fill the reqired fields!";
       }
      
           //verfy user
           
       } 
       ?>
    
       <div class="contact-form">		
           <h2>Contact Us</h2>
           <?php if(isset($msg)){?>
               <div class="alert alert-success alert-dismissible fade show mt-3 "><?php echo $msg;?>
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
               </div>
           <?php } ?>
           <?php if(isset($error)){?>
               <div class="alert alert-danger alert-dismissible fade show mt-3 "><?php echo $error;?>
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
               </div>
           <?php } ?>
           <form method="post" action="<?php echo site_url('contact-us/');?>">
               <div class="mb-3">
                   
                   <input type="text" class="form-control" id="fname" name="fname" placeholder="Name*" required>
               </div>
               <div class="mb-3">
                   
                   <input type="text" class="form-control" id="organization" name="organization" placeholder="Organization">
               </div>
               <div class="mb-3">
                  
                   <input type="email" class="form-control" id="email" name="email" placeholder="Email*" required>
               </div>
               <div class="mb-3">
                  
                   <textarea class="form-control" id="message" name="message" rows="5" placeholder="Message*" required></textarea>
               </div>
               <div class="form-check">
                   <input type="checkbox" class="form-check-input" id="signup" name="signup">
                   <label class="form-check-label" for="signup">Sign me up for emails and updates</label>
               </div>
               <div class="text-center">
               <input type="submit" name="submit_form" class="btn btn-primary mt-4" value="Submit">
               </div>
           </form>
       </div> 
    
       <?php
    }
    add_shortcode( 'custom_contact_form', 'custom_contact_form' ); 
    function add_or_update_member($listid,$email,$firstname) {
        // Check if MC4WP is active
        
         $api_key='3c444e91de05942b83efaa74ae7b31fd-us13';//mc4wp_get_api_v3;
         $api = new MC4WP_API_V3($api_key);
         
         $subscriber_hash=md5( strtolower( trim( $email) ) );
    
            // Define user data
           $subscriber_data = array(
                'email_address' => $email,
                'fname' => $firstname,
                'status'=>'subscribed',
                'merge_fields'=> [
                    'FNAME'=>$firstname
                    ]
            );
            
           
                // Add a new member
                //$result = $api->add_new_list_member($listid, $subscriber_data);
                
                $response=$api->add_list_member($listid, $subscriber_data);
    
                
                
           return true;
       
    }
//}
