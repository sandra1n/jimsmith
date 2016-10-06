<?php
$file = 'uploads/final.txt';
if (isset($_GET['downloadLast']) && file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}
if ($_FILES) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

    $target_file2 = $target_dir . basename($_FILES["fileToUpload2"]["name"]);
    $imageFileType2 = pathinfo($target_file2, PATHINFO_EXTENSION);

    if (isset($_POST["submit"])) {

        if ($_FILES["fileToUpload"]["size"] > 5000 * 1000 || $_FILES["fileToUpload2"]["size"] > 5000 * 1000) {
            echo 'Size of files cannot be more then 5 MB';
            exit;
        }

        if ($imageFileType != "txt" || $imageFileType2 != 'txt') {
            echo "Sorry, only TXT files are allowed.";
            exit;
        }

        if (!($uploaded1 = move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . 'file1'))) {
            echo 'Please attach file 1';
            exit;
        }
        if (!($uploaded2 = move_uploaded_file($_FILES["fileToUpload2"]["tmp_name"], $target_dir . 'file2'))) {
            echo 'Please attach file 2';
            exit;
        }

        if ($uploaded1 && $uploaded2) {
            $file1 = file_get_contents($target_dir . 'file1');
            $file2 = file_get_contents($target_dir . 'file2');
            if ($file1 && $file2) {
                $file1 = explode("\n", $file1);
                $file2 = explode("\n", $file2);
                $final = array_diff($file2, $file1);
                file_put_contents('uploads/final.txt', implode("\n", $final));
                $link2 = "<a href=''>Index</a>";
                $link = "<a href='?downloadLast'>Download</a>";
                echo sprintf("There are %s rows left %s", count($final), $link . ' OR go to ' . $link2);
            }
        }
    }
} else {
    ?>
    <!DOCTYPE html>
    <html>
    <body>
    <form action="" method="post" enctype="multipart/form-data">
        Select file 1 to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <br><br>
        Select file 2 to upload:
        <input type="file" name="fileToUpload2" id="fileToUpload">
        <br><br>
        <input type="submit" value="Upload Files" name="submit">
    </form>

    </body>
    </html>
<?php } ?>