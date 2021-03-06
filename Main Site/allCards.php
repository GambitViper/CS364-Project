<?php

   //CONNECTS TO DATABASE
   class MyDB extends SQLite3 {
      function __construct() {
         $this->open('mtg.db');
      }
   }
   $db = new MyDB();
   if(!$db) {
      echo $db->lastErrorMsg();
   } else {
      //echo "Opened database successfully\n";
   }

   //ADD CARDS TO YOUR COLLECTION
   if (isset($_POST["cardId"])) {
       
        $newCardId = htmlspecialchars($_POST["cardId"]);

        $sql = "SELECT user_id, card_id, collection_id
                FROM User NATURAL JOIN In_Collection
                WHERE user_id == 1 AND collection_id == 1 AND card_id == {$newCardId};";

        $rs = $db->query($sql);

        $row = $rs->fetchArray(SQLITE3_ASSOC);

        if(!$row) {

            $sql = "INSERT INTO In_Collection(card_id, collection_id, amount) VALUES({$newCardId}, 1, 1);";

            $db->query($sql);
            //echo "I added a card";

        } else {

            $sql = "UPDATE In_Collection
                    SET amount = 1 + (SELECT amount
                                      FROM In_Collection 
                                      WHERE collection_id == 1 AND card_id == {$newCardId})
                    WHERE card_id = {$newCardId} AND collection_id == 1;";

            $db->query($sql);
            //echo "I updated collection";
        }
       
   }
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Magic the Gathering Database</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Magic the Gathering<br>Database</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="index.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Interface
      </div>
        
      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link" href="cards.php">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Your Cards</span></a>
      </li>
        
      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link" href="decks.php">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Your Decks</span></a>
      </li>


      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Addons
      </div>

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-fw fa-folder"></i>
          <span>All Cards</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="allCards.php">Cards</a>
            <a class="collapse-item" href="sets.php">Sets</a>
          </div>
        </div>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
            </button>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
            </li>

            <div class="topbar-divider d-none d-sm-block"></div>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">All Magic the Gathering Cards</h1>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Card ID</th>
                      <th>Card Name</th>
                      <th>Mana Cost</th>
                      <th>Rarity</th>
                      <th>CMC</th>
                      <th>Card Type</th>
                      <th>Artist</th>
                      <th>Set Code</th>
                      <th>Card #</th>
                      <th>Price</th>
                      <th>Foil Price</th>
                      <th>Image</th>
                      <th>Add Card</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Card ID</th>
                      <th>Card Name</th>
                      <th>Mana Cost</th>
                      <th>Rarity</th>
                      <th>CMC</th>
                      <th>Card Type</th>
                      <th>Artist</th>
                      <th>Set Code</th>
                      <th>Card #</th>
                      <th>Price</th>
                      <th>Foil Price</th>
                      <th>Image</th>
                      <th> Add Card</th>
                  </tfoot>
                  <tbody>
                  <tbody>
                      <?php
                        $sql = "SELECT card_id, card_number, card_name, mana_cost, rarity, cmc, card_type, artist, set_code, usd_price, usd_foil_price, image_uri FROM Card";
                        $rs = $db->query($sql);
                            
                        while($row = $rs->fetchArray(SQLITE3_ASSOC)){
                            echo "<tr>";
                            echo "<td>". $row['card_id']. "</td>";
                            echo "<td>". $row['card_name']. "</td>";  
                            echo "<td>". $row['mana_cost']. "</td>";  
                            echo "<td>". $row['rarity']. "</td>";  
                            echo "<td>". $row['cmc']. "</td>";  
                            echo "<td>". $row['card_type']. "</td>";  
                            echo "<td>". $row['artist']. "</td>";  
                            echo "<td>". $row['set_code']. "</td>"; 
                            echo "<td>". $row['card_number']. "</td>"; 
                            echo "<td>". $row['usd_price']. "</td>";  
                            echo "<td>". $row['usd_foil_price']. "</td>";  
                            echo "<td><img src=\"". $row['image_uri']. "\" height=\"250\" width=\"150\"></td>";
                            echo "
                              <td>
                                <form method=\"post\" action=\"allCards.php\"> 
                                <input type=\"text\" name=\"cardId\" value=\"". $row['card_id']. "\" hidden />
                                <button type=\"submit\" class=\"btn btn-success btn-icon-split\">
                                    <span class=\"icon text-white-50\">
                                      <i class=\"fas fa-check\"></i>
                                    </span>
                                    <span class=\"text\">Add Card to collection</span>
                                </button>
                                </form>
                              </td>";
                            echo "</tr>";
                        }
                      ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
    
  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>
