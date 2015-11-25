<?php
/**
 * This page displays all the messages attached with a project
 *
 */

$msg_obj = CPM_Message::getInstance();
$pro_obj = CPM_Project::getInstance();

cpm_get_header( __( 'Discussion', 'cpm' ), $project_id );
$can_create = cpm_user_can_access( $project_id, 'create_message' );
?>
<div class="cpm-row cpm-message-page">
    <div class="cpm-col-3 cpm-message-list">
        <?php if ( $can_create ) { ?>
        <div> 
            <a class="cpm-btn cpm-plus-white cpm-add-message " href="#" > <?php _e( 'ADD NEW DISCUSSION', 'cpm' ); ?> </a>
        </div>
    <?php } ?>
        
         <?php
    if ( cpm_user_can_access( $project_id, 'msg_view_private' ) ) {
        $messages = $msg_obj->get_all( $project_id, true );
    } else {
        $messages = $msg_obj->get_all( $project_id );
    }
    if($messages) { 
        echo "<ul>";
        foreach ($messages as $message) {
            $private_class = ( $message->private == 'yes' ) ? 'cpm-lock' : 'cpm-unlock';
        ?> 
        <li itemid="<?php echo $project_id ; ?>"> 
                <div class="cpm-col-2">  
                    <?php echo cpm_url_user( $message->post_author, true, 32 ); ?> 
                </div>
                <div class="cpm-col-10"> 
                    <div> 
                        <a href="JavaScript:void(0)"> <?php echo cpm_excerpt( $message->post_title, 50 ); ?>  </a>
                        <span class="cpm-message-action">
                            <?php if ( $message->post_author == get_current_user_id() || cpm_user_can_access( $project_id ) ) { ?>
                    <a href="JavaScript:void(0)" class="delete-message cpm-icon-delete" title="<?php esc_attr_e( 'Delete this message', 'cpm' ); ?>" <?php cpm_data_attr( array('msg_id' => $message->ID, 'project_id' => $project_id, 'confirm' => __( 'Are you sure to delete this message?', 'cpm' )) ); ?>>
                        <span><?php _e( 'Delete', 'cpm' ); ?></span>
                    </a>
                    <span class="<?php echo $private_class; ?>"></span>
                <?php } ?>
                        </span>
                    </div>
                    <div> 
                        <?php echo cpm_excerpt( $message->post_content, 20 ) ?> 
                    </div>
                    <div> 
                        <?php echo date_i18n( 'j M, Y', strtotime( $message->post_date ) ); ?>
                        <span class="comment-count"><?php echo cpm_get_number( $message->comment_count ); ?></span>
                    </div>  
                </div>
                <div class="clear"></div>
            </li>
        <?php }
        echo "</ul>";
    } 
    ?>
    </div>
    
    <div class="cpm-col-9 cpm-message-body">
        Right  Part 
    </div>
    <div class="clear"></div>
</div>


<h3 class="cpm-nav-title">
    <?php
    _e( 'Discussion', 'cpm' );

    if ( $can_create ) {
        ?>
        <a class="add-new-h2 cpm-new-message-btn" href="#"><?php _e( 'Add New', 'cpm' ); ?></a>
    <?php } ?>
</h3>







<table class="cpm-messages-table">
    <?php
    if ( cpm_user_can_access( $project_id, 'msg_view_private' ) ) {
        $messages = $msg_obj->get_all( $project_id, true );
    } else {
        $messages = $msg_obj->get_all( $project_id );
    }

    foreach ($messages as $message) {
        $private_class = ( $message->private == 'yes' ) ? 'cpm-lock' : 'cpm-unlock';
        ?>
        <tr>
            <td class="author">
                <span class="cpm-avatar"><?php echo cpm_url_user( $message->post_author, true, 32 ); ?></span>
            </td>
            <td class="message">
                <a href="<?php echo cpm_url_single_message( $project_id, $message->ID ); ?>">
                    <span class="title"><?php echo cpm_excerpt( $message->post_title, 50 ); ?></span>
                    <?php
                    if ( $message->post_content ) {
                        printf( '<span class="excerpt"> - %s</span>', cpm_excerpt( $message->post_content, 70 ) );
                    }
                    ?>
                </a>
            </td>
            <td class="date"><span><?php echo date_i18n( 'j M, Y', strtotime( $message->post_date ) ); ?></span></td>
            <td class="comment-count"><span><?php echo cpm_get_number( $message->comment_count ); ?></span></td>
            <td class="cpm-actions">
                <?php if ( $message->post_author == get_current_user_id() || cpm_user_can_access( $project_id ) ) { ?>
                    <a href="#" class="delete-message cpm-icon-delete" title="<?php esc_attr_e( 'Delete this message', 'cpm' ); ?>" <?php cpm_data_attr( array('msg_id' => $message->ID, 'project_id' => $project_id, 'confirm' => __( 'Are you sure to delete this message?', 'cpm' )) ); ?>>
                        <span><?php _e( 'Delete', 'cpm' ); ?></span>
                    </a>
                    <span class="<?php echo $private_class; ?>"></span>
                <?php } ?>
            </td>
        </tr>
        <?php
    }
    ?>
</table>

<?php
if ( !$messages ) {
    cpm_show_message( __( 'No messages found! How about adding one?', 'cpm' ) );
}
