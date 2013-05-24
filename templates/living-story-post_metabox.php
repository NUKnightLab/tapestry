<table> 
    <tr valign="top">
        <th class="metabox_label_column">
            <label for="tap_post_summary"> Summary for Post In Stream</label>
        </th>
        <td>
            <input type="text" id="tap_post_summary" name="tap_post_summary" value="<?php echo @get_post_meta($post->ID, 'tap_post_summary', true); ?>" />
        </td>
    <tr>
    <tr valign="top">
        <th class="metabox_label_column">
            <label for="tap_post_headline"> Headline for Post In Stream</label>
        </th>
        <td>
            <input type="text" id="tap_post_headline" name="tap_post_headline" value="<?php echo @get_post_meta($post->ID, 'tap_post_headline', true); ?>" />
        </td>
    <tr>
    <tr valign="top">
        <th class="metabox_label_column">
            <label for="tap_post_altdate"> Alternate Date for Post in Stream </label>
        </th>
        <td>
            <input type="text" id="tap_post_altdate" name="tap_post_altdate" value="<?php echo @get_post_meta($post->ID, 'tap_post_altdate', true); ?>" />
        </td>
    <tr>           
    <tr valign="top">
        <th class="metabox_label_column">
            <label for="tap_post_priority"> Priority for Post in Stream (1 or 5) </label>
        </th>
        <td>
            <input type="text" id="tap_post_priority" name="tap_post_priority" value="<?php echo @get_post_meta($post->ID, 'tap_post_priority', true); ?>" />
        </td>
    <tr>                     
</table>