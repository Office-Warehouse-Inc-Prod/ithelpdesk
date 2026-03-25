 <?php
	include 'db.php';
	$category_id=$_POST["category_id"];
	$result = mysqli_query($concat,"SELECT * FROM ld_category_sub where cat_id=$category_id");
	// $result = mysqli_query($concat,"SELECT * FROM subcat where cat_id=$category_id AND sub_id NOT IN ('15','28','34','')");
?>
<option value="">Select SubCategory</option>
<?php
while($row = mysqli_fetch_array($result)) {
?>
	<option value="<?php echo $row["sub_id"];?>"><?php echo $row["sub_cat"];?></option>
<?php
}
?>