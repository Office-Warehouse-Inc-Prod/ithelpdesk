 <?php
	include 'db.php';
	$category_id=$_POST["category_id"];
	$subnum=$_POST["sub_num"];
	$result = mysqli_query($concat,"SELECT * FROM subcat where cat_id=$category_id and sub_id =$subnum");
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