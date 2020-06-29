<html>

<head>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
  <h1>Report</h1>
  <ul>
    <?php 
  
  //Executes the following code if file should be deleted
  if($_POST['submit'] == 'delete'){
    $del = $_POST['input'];
    shell_exec('cd cronjobs && rm '.$del);
    echo("<script>location.href = '/cron.php';</script>");
  }

  //Executes if Cron Job should be run
  if($_POST['submit'] == 'run'){
    $file = 'cronjobs/'.$_POST['input'];
    $command = file_get_contents($file);
    $commandWithSemicolons = str_replace(array("\r\n", "\r", "\n"), ';', trim($command)); 
    $cleanCommand = explode(";", $commandWithSemicolons);
    foreach($cleanCommand as $value){
      print '<br/>'.$value;
      echo '<pre>'.shell_exec($value).'</pre>';
    }
    
  }

  //Executes if a new Cron Job has been written and submitted in the form 
  if($_POST['submit'] == 'Save'){
    if(empty($_POST['cronJobName']) || empty($_POST['cronJob'])){
      if(empty($_POST['cronJobName'])){
        print('<li>Please enter a name for your Cron Job</li>');
      } 
      if(empty($_POST['cronJob'])){
        print('<li>Please enter your Cron Job</li>');
      }
      echo("<script>setTimeout(function(){window.location.href = '/cron.php';}, 5000);</script>");
    } else {

      $fileName = $_POST['cronJobName'];
      $fileContent = $_POST['cronJob'];
      $fileType = strtolower(pathinfo($fileName,PATHINFO_EXTENSION));

      //Verifies if $_POST['cronJobName'] has already an .sh-ending, otherwise it will append it
      if($fileType == "sh"){
        shell_exec('cd cronjobs ; printf \''.$fileContent.'\' > '.$fileName.'');
      } else {
        shell_exec('cd cronjobs ; printf \''.$fileContent.'\' > '.$fileName.'.sh');
      }
      echo("<script>location.href = '/cron.php?saved=true';</script>");
    }
  }


  // Executes the following code only if a new Cron Job has been submitted via the Uploader
  if(isset($_POST['uploadCron'])){
    // Setting initial values
    $target_dir = './cronjobs/';
    $target_file = $target_dir . basename($_FILES['file-upload']['name']);
    echo($target_file);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    if($fileType == "sh"){
        $uploadOk = 1;
      } else {
          echo '<li>File is not an .sh-file.</li>';
          $uploadOk = 0;
        }

    // Check if file already exists
    if (file_exists($target_file)) {
      echo '<li>Sorry, file already exists.</li>';
      $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if($uploadOk == 0) {
      echo '<li>Your file was not uploaded.</li>';
      echo '<li>Please try again in 5 seconds</li>';
      echo "<script>setTimeout(function(){window.location.href = '/cron.php';}, 5000);</script>";

    // if everything is ok, try to upload file
    } else {
      if(move_uploaded_file($_FILES['file-upload']['tmp_name'], $target_file)) {
        echo 'The file '. basename($_FILES['file-upload']['name']). ' has been uploaded.';
        echo("<script>location.href = '/cron.php';</script>");
      } else {
        echo('Sorry, there was an error uploading your file.');
      }
    }
  }
?>
  </ul>
</body>

</html>