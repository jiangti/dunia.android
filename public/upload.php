<?php 

$post = $_POST;

$path = dirname(__DIR__);

foreach ($_FILES as $file) {
	
	$source = $file['tmp_name'];
	$targetFolder = $path . '/var/upload/' . $post['uniq'] . '/' . $file['name'];
	mkdir(dirname($targetFolder), 0777, true);
	copy($source, $targetFolder);
	$post['dateAdded'] = time();

	file_put_contents(dirname($targetFolder) . '/meta.json', json_encode($post));
}

?>
