<!--https://premium.wpmudev.org/blog/wordpress-admin-tables/-->
<!--https://wordpress.stackexchange.com/questions/1413/creating-a-table-in-the-admin-style-->

<?php
global $wpdb;
$table = $wpdb->prefix.'mwcustomform'; 
$result = $wpdb->get_results ( "SELECT * FROM $table ");

?>
<h2>Form List</h2>
<table class="widefat fixed" cellspacing="0">
    <thead>
    <tr>

            <th id="columnname" class="manage-column column-columnname" scope="col" style="text-align:center" width="50">S.No</th>
            <th id="columnname" class="manage-column column-columnname" scope="col">Name</th>
            <th id="columnname" class="manage-column column-columnname" scope="col">Email</th>
            <th id="columnname" class="manage-column column-columnname" scope="col">Phone</th>

    </tr>
    </thead>

    <tfoot>
    <tr>

            <th class="manage-column column-columnname" scope="col" width="50" style="text-align:center">S.No</th>
            <th class="manage-column column-columnname" scope="col">Name</th>
            <th class="manage-column column-columnname" scope="col">Email</th>
            <th class="manage-column column-columnname" scope="col">Phone</th>

    </tr>
    </tfoot>

    <tbody>
<?php 
$sn_count = 1;
foreach($result as $value){ ?>
        <tr class="alternate">
            <th class="check-column" scope="row" style="text-align:center"><?php echo $sn_count; ?></th>
            <th class="check-column" scope="row"><?php echo $value->name; ?></th>
            <td class="column-columnname"><?php echo $value->email; ?></td>
            <td class="column-columnname"><?php echo $value->phone; ?></td>
        </tr>	
<?php 
$sn_count++;
} ?>	

    </tbody>
</table>

