<?php
   class MyDB extends SQLite3 {
      function __construct() {
         $this->open('mtg.db');
      }
   }
   $db = new MyDB();
   if(!$db) {
      echo $db->lastErrorMsg();
   } else {
      echo "Opened database successfully\n";
   }

   if (isset($_POST["cardIdAdd"])) {
       
    $newCardIdAdd = htmlspecialchars($_POST["cardIdAdd"]);
       
    $sql = " UPDATE In_Collection
             SET amount = 1 + (SELECT amount
                               FROM In_Collection 
                               WHERE collection_id == 1 AND card_id == {$newCardIdAdd})
             WHERE card_id = {$newCardIdAdd};";
       
    $db->query($sql);
       
    echo "I added a card";
   }

   if (isset($_POST["cardIdRemove"])) {
       
    $newCardIdRemove = htmlspecialchars($_POST["cardIdRemove"]);
       
    $sql = "UPDATE In_Collection
            SET amount = (SELECT amount
                          FROM In_Collection
                          WHERE collection_id == 1 AND card_id == {$newCardIdRemove}) - 1
            WHERE card_id == {$newCardIdRemove}";
       
    $db->query($sql);
       
    echo "I removed a card";
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

            <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Your Cards</h1>
          <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below. For more information about DataTables, please visit the <a target="_blank" href="https://datatables.net">official DataTables documentation</a>.</p>

          <!-- Content Row -->
          <div class="row">
              
            <!-- % Gathered -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Amount of cards Gathered</div>
                      <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                      <?php
                        $sql = "SELECT user_count.number * 1.0 / card_count.number * 100 AS percent_collected
                                FROM (SELECT count(*) AS number
                                      FROM User NATURAL JOIN In_Collection NATURAL JOIN Card
                                      WHERE User.user_id == 1 ) AS user_count JOIN (SELECT count(*) AS number
                                                                                    FROM Card) AS card_count";
                        $rs = $db->query($sql);
                            
                        while($row = $rs->fetchArray(SQLITE3_ASSOC)){
                            echo "<div class=\"h5 mb-0 font-weight-bold text-gray-800\">". $row['percent_collected']. "</div>";
                        }
                      ?>
                        </div>
                        <div class="col">
                      <?php
                        $sql = "SELECT user_count.number * 1.0 / card_count.number * 100 AS percent_collected
                                FROM (SELECT count(*) AS number
                                      FROM User NATURAL JOIN In_Collection NATURAL JOIN Card
                                      WHERE User.user_id == 1 ) AS user_count JOIN (SELECT count(*) AS number
                                                                                    FROM Card) AS card_count";
                        $rs = $db->query($sql);
                            
                        while($row = $rs->fetchArray(SQLITE3_ASSOC)){
                            echo "
                                  <div class=\"progress progress-sm mr-2\">
                                    <div class=\"progress-bar bg-info\" role=\"progressbar\" style=\"width:".$row['percent_collected']. "%\" aria-valuenow=\"50\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div>
                                  </div>
                                 ";
                        }
                      ?>
                        </div>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
              
              
            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Colletion Size</div>
                      <?php
                        $sql = "SELECT count(*) AS number
                                  FROM User NATURAL JOIN In_Collection
                                  WHERE User.user_id == 1 ";
                        $rs = $db->query($sql);
                        if ($row['percent_collected'] == null){ 
                            echo "<div class=\"h5 mb-0 font-weight-bold text-gray-800\">0</div>";
                        } else{
                            echo "<div class=\"h5 mb-0 font-weight-bold text-gray-800\">". $row['percent_collected']. "</div>";
                        }
                      ?>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
              
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Collection Price (None Foil Cards)</div>
                        
                      <?php
                        $sql = "SELECT sum(usd_price * amount) AS usdPrice
                                FROM User NATURAL JOIN In_Collection NATURAL JOIN Card
                                WHERE User.user_id == 1;";
                        
                        $rs = $db->query($sql);
                        
                        if ($row['usdPrice'] == null){ 
                            echo "<div class=\"h5 mb-0 font-weight-bold text-gray-800\">$0</div>";
                        } else{
                            echo "<div class=\"h5 mb-0 font-weight-bold text-gray-800\">$". $row['usdPrice']. "</div>";
                        }
                            
                      ?>
                        
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Collection Price (Foil Cards)</div>
                        
                      <?php
                        $sql = "SELECT sum(usd_foil_price * amount) AS usdPriceFoil
                                FROM User NATURAL JOIN In_Collection NATURAL JOIN Card
                                WHERE User.user_id == 1;";
                        
                        $rs = $db->query($sql);
                        
                        if ($row['usdPrice'] == null){ 
                            echo "<div class=\"h5 mb-0 font-weight-bold text-gray-800\">$0</div>";
                        } else{
                            echo "<div class=\"h5 mb-0 font-weight-bold text-gray-800\">$". $row['usdPriceFoil']. "</div>";
                        }
                            
                      ?>
                        
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          </div>
            
          <!-- Content Row -->
          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Cards not in decks</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Card ID</th>
                      <th>Card Name</th>
                      <th># of Cards</th>
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
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Card ID</th>
                      <th>Card Name</th>
                      <th># of Cards</th>
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
                  </tfoot>
                  <tbody>
                      <?php
                        $sql = "SELECT Card.card_id, card_number, card_name, mana_cost, rarity, cmc, card_type, artist, set_code, usd_price, usd_foil_price, image_uri 
                                FROM User NATURAL JOIN In_Collection LEFT OUTER JOIN In_Deck ON In_Deck.card_id == In_Collection.card_id NATURAL JOIN Card
                                WHERE deck_id IS NULL AND User.user_id == 1;";
                      
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
                            echo "<td><img src=\"". $row['image_uri']. "\" height=\"150\" width=\"50\"></td>";
                            echo "
                              <td>
                                <form method=\"post\" action=\"allCards.php\"> 
                                <input type=\"text\" name=\"cardIdAdd\" value=\"". $row['card_id']. "\" hidden />
                                <button type=\"submit\" class=\"btn btn-success btn-icon-split\">
                                    <span class=\"icon text-white-50\">
                                      <i class=\"fas fa-check\"></i>
                                    </span>
                                    <span class=\"text\">Add Card</span>
                                </button>
                                </form>
                              </td>
                                <form method=\"post\" action=\"allCards.php\"> 
                                <input type=\"text\" name=\"cardIdRemove\" value=\"". $row['card_id']. "\" hidden />
                                <button type=\"submit\" class=\"btn btn-danger btn-icon-split\">
                                    <span class=\"icon text-white-50\">
                                      <i class=\"fas fa-trash\"></i>
                                    </span>
                                    <span class=\"text\">Remove Card</span>
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

          <!-- Content Row -->
          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">All cards in collection</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Card ID</th>
                      <th>Card Name</th>
                      <th># of Cards</th>
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
                      <th>Add/Remove Card</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Card ID</th>
                      <th>Card Name</th>
                      <th># of Cards</th>
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
                      <th>Add/Remove Card</th>
                  </tfoot>
                  <tbody>
                      <?php
                        $sql = "SELECT Card.*
                                FROM User NATURAL JOIN In_Collection LEFT OUTER JOIN In_Deck ON In_Deck.card_id == In_Collection.card_id NATURAL JOIN Card
                                WHERE User.user_id == 1;";
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
                            echo "<td><img src=\"". $row['image_uri']. "\" height=\"150\" width=\"50\"></td>";
                            echo "
                              <td>
                                <form method=\"post\" action=\"allCards.php\"> 
                                <input type=\"text\" name=\"cardIdAdd\" value=\"". $row['card_id']. "\" hidden />
                                <button type=\"submit\" class=\"btn btn-success btn-icon-split\">
                                    <span class=\"icon text-white-50\">
                                      <i class=\"fas fa-check\"></i>
                                    </span>
                                    <span class=\"text\">Add Card</span>
                                </button>
                                </form>
                              </td>
                                <form method=\"post\" action=\"allCards.php\"> 
                                <input type=\"text\" name=\"cardIdRemove\" value=\"". $row['card_id']. "\" hidden />
                                <button type=\"submit\" class=\"btn btn-danger btn-icon-split\">
                                    <span class=\"icon text-white-50\">
                                      <i class=\"fas fa-trash\"></i>
                                    </span>
                                    <span class=\"text\">Remove Card</span>
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
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

    </div>
    <!-- End of Content Wrapper -->
    
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
