<h1>Download Categories</h1>

<?php
	if(!$id) {
		$categories = $categories->getCategories();
?>
<table class="index" id="downloads-categories-all">
	<thead>
		<th width="80%">Name</th>
		<th width="20%">Edit</th>
	</thead>
<?php foreach($categories as $category) { ?>
	<tbody>
		<tr class="<?php echo odd_even(); ?>">
			<td><a href="<?php echo $url .'/'. $category['category_id']; ?>"><?php echo $category['name']; ?></a></td>
			<td rowspan="2"><a href="<?php echo $url .'/'. $category['category_id']; ?>">Edit</a> | <a href="<?php echo $url .'/delete/'. $category['category_id']; ?>" onClick="return confirm('Are you sure you want to delete this category?');">Delete</a></td>
		</tr>
		<tr>
			<td><small><?php echo $category['description']; ?></small></td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php
	}
	else {
		$category = $categories->getCategories($id);
		$category = $category['0'];
		if($id == 'add') {
			$action = 'addCategory';
			echo '<h3>Add a Category</h3>';
		}
		else {
			$action = 'edit';
			echo '<h3>Currently Editing: '.$category['name'].'</h3>';
		}
?>

<form action="<?php echo $url . $action ?>" method="POST">
	<input name="id" value="<?php echo $category['category_id']; ?>" type="hidden" />
	<table class="fieldset" id="downloads-categories-edit">
		<tr>
			<td class="label">Name</td>
			<td class="field"><input type="text" class="textbox" name="name" value="<?php echo $category['name']; ?>"></td>
			<td class="help">This is the name of the Category</td>
		</tr>
		<tr>
			<td class="label">Description</td>
			<td class="field"><textarea name="description" class="textbox"><?php echo $category['description']; ?></textarea></td>
			<td class="help">This is a description for the category, which may be useful for the frontend. <strong>No HTML please.</strong></td>
		</tr>
		<tr>
			<td class="label"></td>
			<td class="field" colspan="2"><button class="Submit">Update</button> or <a href="<?php echo $url; ?>">cancel changes</a></td>
		</tr>
	</table>
</form>

<?php	}
	