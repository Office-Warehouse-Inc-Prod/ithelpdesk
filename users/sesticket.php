<?php
include 'db.php';
if (isset($_POST['tktval'])) {
  $tktval = $_POST['tktval'];
  $query = "SELECT * FROM images WHERE ticket_no = ? ORDER BY uploaded_on DESC";
  $run = $concat->prepare($query);
  $run->bind_param('s', $tktval);
  $run->execute();
  $rs = $run->get_result();

  if ($rs->num_rows > 0) {
    $output = '<div class="attachment-grid">';
    while ($row = $rs->fetch_assoc()) {
      $origName = htmlspecialchars($row['files_name']);
      $storedPath = trim($row['files_tmp']);
      $fileExtension = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
      $fileUrl = htmlspecialchars($storedPath);
      $filePath = $storedPath;

      if (file_exists($filePath)) {
        switch ($fileExtension) {
          case 'jpg':
		  case 'JPG':
          case 'jpeg':
          case 'png':
          case 'gif':
            $output.= "<br />\n";
            $output.= "<a href='". $filePath. "' target='_blank'><img src='". $filePath. "' style='width: 470px; height: 300px;' ></a><br>";
            break;
          case 'pdf':
            $output.= "<br />\n";
			$output.= "file name: ". $filename . "<br>"; // to show file name
            $output.= "<a href='". $filePath. "' target='_blank'><img src='../icons/pdf.png' width='200' height='100' alt='Open PDF'></a><br>";
            break;
		case 'txt':
			$output.= "<br />\n";
			$output.= "file name: ". $filename . "<br>"; // to show file name
			$output.= "<a href='". $filePath. "' target='_blank'><img src='../icons/text.png' width='200' height='100' alt='Open TEXT'></a><br>";
			break;
          case 'doc':
          case 'docx':
            $output.= "<br />\n";
			$output.= "file name: ". $filename . "<br>"; // to show file name
            $output.= "<a href='". $filePath. "' target='_blank'><img src='../icons/word.png' width='200' height='100' alt='Open Word Document'></a><br>";
            break;
          case 'xls':
          case 'xlsx':
            $output.= "<br />\n";
			$output.= "file name: ". $filename . "<br>"; // to show file name
            $output.= "<a href='". $filePath. "' target='_blank'><img src='../icons/excel.png' width='200' height='100' alt='Open Excel Spreadsheet'></a><br>";
            break;
          default:
            $output.= "<br />\n";
            $output.= "<img src='unknown-icon.png' width='20' height='20'> ". $filename. " (Unsupported file type: ". $fileExtension. ")<br>";
        }
      } else {
        $output.= "<br />\n";
        $output.= "File not found: ". $filename. "<br>";
      }
    }
    echo $output;
  } else {
    echo "No file attached.";
  }
} else {
  echo "Error: tktval value not set.";
}
?>