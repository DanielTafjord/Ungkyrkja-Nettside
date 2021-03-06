<?php
include_once('account/login.php');
$con = mysqli_connect('localhost','ungkyrkja','ungkyrkja','ungkyrkja');
if(!$con){
  die('Failed to connect to database: ' . mysqli_error($con));
}
$isadmin = false;
if (!empty($_COOKIE['auth-u'])) {
  $authu = $_COOKIE['auth-u'];
  $queryusers = mysqli_query($con, "SELECT * FROM users WHERE user = '$authu'");
  if($queryusers){
  $row = mysqli_fetch_array($queryusers);
    if ($row['role'] == 1) {
      $isadmin = true;
    }
  }
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="57x57" href="icon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="icon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="icon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="icon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="icon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="icon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="icon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="icon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="icon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="icon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="icon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="icon/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#222">
    <meta name="msapplication-TileImage" content="icon/ms-icon-144x144.png">
    <meta name="theme-color" content="#222">
    <title>Ungkyrkja</title>
    <script src="bower_components/webcomponentsjs/webcomponents-lite.min.js"></script>
    <link rel="import" href="components/header-imports.html">
  </head>
  <style media="screen">
    .p-div {
      margin-top: 20px;
      margin-left: auto;
      margin-right: auto;
      width: 100%;
      max-width: 900px;
    }
    .p-img {
      width: 100%;
      height: 100%;
    }
    .p-div-com {
      background-color:#eee;
    }
  </style>
  <body>
    <!--Import navbar-->
    <?php
    $site_location = '/bilder.php';
    include 'components/navbar.php';
    include 'components/alert.php';
    ?>

    <!--Content here-->
    <section align="center">
			<div class="row">
	      <?php
	        $images = mysqli_query($con, "SELECT * FROM bilder");

	        # check if img is not set
	        if(!isset($_GET['img'])) {
	          while($rows = mysqli_fetch_array($images)) {
	            #if file exist show image
	            if(file_exists('bilder/' . $rows['img'])) {
								echo "<div class='col-md-3' style='margin-bottom:40px;'><a href='bilder/" . $rows['img'] . "' data-lightbox='1'><img src='bilder/" . $rows['img'] . "'></a></div>";
	            } else {
	              echo "<p>Fant ingen bilder!</p>";
	            }
	          }
	        }
	      ?>
			</div>
    </section>
    <?php

    # Check if img is set
    if (isset($_GET['img'])) {
      $get_image = $_GET['img'];
      $sql_image = mysqli_query($con, "SELECT * FROM bilder WHERE id = {$get_image}");
      while($row = mysqli_fetch_array($sql_image, MYSQLI_ASSOC)) :
        echo "<div align='center' class='p-div'>";
          echo "<img class='p-img' src='bilder/" . htmlentities($row['img']) . "'>";
          echo "<p style='margin-top:40px;background-color:#f4f4f4;padding:30px;width:90%;font-size:18px;'>" . htmlentities($row['description']) . "</p>";
        echo "</div>";
        if(!empty($_COOKIE['auth-u'])){
          if($isadmin == true){
            echo "<button class='btn btn-danger' data-toggle='modal' data-target='#myModal'>Slett bilde!</button>";
          }
        } ?>
        <div align="center" style="background-color:#f4f4f4;">
        <div id="disqus_thread" style="width:90%;margin-top:50px;padding-top:20px;"></div>
          <script>
              /**
               *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
               *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables
               */
              /*
              var disqus_config = function () {
                  this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
                  this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
              };
              */
              (function() {  // DON'T EDIT BELOW THIS LINE
                  var d = document, s = d.createElement('script');

                  s.src = '//danieltafjord.disqus.com/embed.js';

                  s.setAttribute('data-timestamp', +new Date());
                  (d.head || d.body).appendChild(s);
              })();
          </script>
          <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
        </div>
        <?php
      endwhile;
    }

    ?>

    <?php
    if($isadmin == true) {
        if(isset($_POST['submit'])) {
          $getimg = $_POST['img'];
            mysqli_query($con, "DELETE FROM bilder WHERE id = '$getimg'");
            echo "it worked" . $_POST['img'];
          }
    }
    ?>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Advarsel</h4>
          </div>
          <div class="modal-body">
            <p>Er du sikker på at du vil slette dette bilde?</p>
          </div>
          <div class="modal-footer">
            <form class="form-inline" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
              <div class="form-group">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="text" hidden name="img" value="<?php $_GET['img']; ?>">
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-danger" name="submit">Ja</button>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>

    <!--Import footer-->
    <?php include 'components/footer.php'; ?>
    <link rel="import" href="components/main-scripts.html">
    <script type="text/javascript">
    $('#myModal').on('hidden.bs.modal', function (e) {
      $('#myModal').modal('hide')
    })
    </script>
    <script id="dsq-count-scr" src="//danieltafjord.disqus.com/count.js" async></script>
  </body>
</html>
