<html>

<head>
  <title>Cron Jobs</title>
  <link rel="stylesheet" type="text/css" href="style.css?<?php echo time(); ?>">
</head>

<body>
  <div id="container">
  <h1>Cron Jobs</h1>
  <p class="information">Already existing Cron Jobs:</p>
  <ul id="listCronJobs">

    <?php
  $dir = './cronjobs';
  $files = scandir($dir);
  $filteredFiles = preg_grep('/\.sh$/', $files);
  foreach($filteredFiles as $input){
    print('<li>');
    print('<form action="actions.php" id="frm_RegisteredForm">');
    print('<span class="cronName">');
      print($input);
    print('</span>');
    print('<button name="submit" value="edit" formaction="cron.php" formmethod="post">Edit</button>');
    print('<button name="submit" value="run" formmethod="post" formtarget="_blank">Run</button>');
    print('<button name="submit" value="delete" formmethod="post">Delete</button>');
    print('<input type="hidden" name="input" value="'.$input.'">');
    print('</form>');
    print('</li>');
  }
?>
  </ul>

  <h2>Write new Cron Job</h2>
  <form action="actions.php" method="post" enctype="multipart/form-data" id="cronEditor">
    <fieldset>
      <label for="cronJobName">Cron Job name</label><br>
      <input type="text" id="cronJobName" name="cronJobName" size="30" value="<?php echo $_POST['input']; ?>" /><br />
      <label for="cronJob">Write your Cron Job</label><br />
      <textarea name="cronJob" id="cronJob" cols="80" rows="20"><?php echo(shell_exec('cd cronjobs; cat '.$_POST['input'])); ?></textarea><br>
      <input type="submit" name="submit" id="submit" value="Save" />
    </fieldset>
  </form>

  <?php if($_GET['saved']) { echo '<p class="feedback">The file has been saved successfully</p>';} ?>

  <h2>Upload new Cron Job-File</h2>
  <p class="information"><b>Attention:</b> only .sh-files are allowed</p>

  <form action="actions.php" method="post" id="uploader" name="uploader" enctype="multipart/form-data">
    <fieldset>
      <input type="file" id="file-upload" name="file-upload" class="inputfile"/>
      <label for="file-upload" id="fileLabel">Select .sh-file to upload</label>
      <input type="submit" value="Upload File" id="uploadCron" name="uploadCron">
    </fieldset>
  </form>


<script>
    var input = document.getElementById('file-upload');
    var fileLabel = document.getElementById('fileLabel');

    input.addEventListener('change', showFileName);

    function showFileName(event) {

      // the change event gives us the input it occurred in 
      var input = event.srcElement;

      // the input has an array of files in the `files` property, each one has a name that you can use. We're just using the name here.
      var fileName = input.files[0].name;

      // use fileName however fits your app best, i.e. add it into a div
      fileLabel.innerHTML = fileName;
    }
  </script>
</body>

</html>