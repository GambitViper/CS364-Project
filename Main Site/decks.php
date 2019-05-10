<?php
   //DATABASE CONNECTION
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

   //THIS WILL ADD A CARD FROM THE DECK
   if (isset($_POST["addCardFromDeck"])) {
       
    $addedCard = htmlspecialchars($_POST["addCardFromDeck"]);
       
    $sql = "UPDATE In_Deck
            SET card_amount = 1 + (SELECT card_amount
                                   FROM In_Deck
                                   WHERE deck_id == 1 AND card_id == {$addedCard})
            WHERE card_id == {$addedCard} AND deck_id == 1";
       
    $db->query($sql);
       
    //echo "I added a card to deck";
   }

   //THIS WILL REMOVE A CARD FROM THE DECK
   if (isset($_POST["removeCardFromDeck"])) {
       
    $removedCard = htmlspecialchars($_POST["removeCardFromDeck"]);
       
    $sql = "UPDATE In_Deck
            SET card_amount = (SELECT card_amount
                               FROM In_Deck
                               WHERE deck_id == 1 AND card_id == {$removedCard}) - 1
            WHERE card_id == {$removedCard} AND deck_id == 1";
       
    $db->query($sql);
       
    $sql = "DELETE FROM In_Deck
            WHERE card_amount <= 0;";
    $db->query($sql);
    //echo "I removed a card from deck";
   }

   //ADD DECK LOSS
   if (isset($_POST["addDeckLoss"])) {
       
    $addDeckLoss = htmlspecialchars($_POST["addDeckLoss"]);
       
    $sql = "UPDATE Deck
            SET deck_losses = 1 + (SELECT deck_losses
                                   FROM In_Deck
                                   WHERE deck_id == 1)
            WHERE deck_id == 1;";
       
    $db->query($sql);
       
    //echo "I added a deck loss";
   }

   //ADD DECK WIN
   if (isset($_POST["addDeckWin"])) {
       
    $addDeckLoss = htmlspecialchars($_POST["addDeckWin"]);
       
    $sql = "UPDATE Deck
            SET deck_wins = 1 + (SELECT deck_wins
                                   FROM In_Deck
                                   WHERE deck_id == 1)
            WHERE deck_id == 1;";
       
    $db->query($sql);
    //echo "I added a deck win";
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

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800">Your Decks</h1>
<!--
          <a href="#" class="btn btn-success btn-icon-split">
            <span class="icon text-white-50">
              <i class="fas fa-check"></i>
            </span>
            <span class="text">Add Deck</span>
          </a>
            
          <a href="#" class="btn btn-danger btn-icon-split">
            <span class="icon text-white-50">
              <i class="fas fa-trash"></i>
            </span>
            <span class="text">Remove Deck</span>
          </a>-->
            
          <!-- DataTales Example -->
          <div class="card shadow mb-4">
              <?php
                echo '<div class="card-header py-3">';
                $sql = "SELECT deck_id, deck_name, deck_wins, deck_losses, creation_date, card_name, card_amount, card_id
                        FROM User NATURAL JOIN Deck NATURAL JOIN In_Deck NATURAL JOIN Card
                        WHERE User.user_id == 1";
                
                $rs1 = $db->query($sql);
                while($row = $rs1->fetchArray(SQLITE3_ASSOC)){
                    if ($row['deck_id'] != null){ 
                        echo "<h6 class=\"m-0 font-weight-bold text-primary\">Deck ID: ".$row['deck_id']." Deck Name: ". $row['deck_name']. " W: ". $row['deck_wins']. " L: ". $row['deck_losses']. " Created: ". $row['creation_date'] ."</h6>";
                        break;
                    } else{
                        echo "<h6 class=\"m-0 font-weight-bold text-primary\">No Deck</h6>";
                    }
                }
                
                
                echo '</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                        <th>Card Name</th>
                                        <th>Amount</th>
                                        <th>Add/Remove Card</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                        <th>Card Name</th>
                                        <th>Amount</th>
                                        <th>Add/Remove Card</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>';
              
                                    while($row = $rs1->fetchArray(SQLITE3_ASSOC)){
                                        echo '<tr>';
                                        echo '<th>'. $row['card_name']. '</th>';
                                        echo '<th>'. $row['card_amount']. '</th>';
                                        echo "<td>
                                                <form method=\"post\" action=\"decks.php\">
                                                    <input type=\"text\" name=\"addCardFromDeck\" value=\"". $row['card_id']. "\" hidden />
                                                    <button type=\"submit\" class=\"btn btn-success btn-icon-split\">
                                                        <span class=\"icon text-white-50\">
                                                            <i class=\"fas fa-check\"></i>
                                                        </span>
                                                        <span class=\"text\">+1</span>
                                                    </button>
                                                </form>
                                                <form method=\"post\" action=\"decks.php\">
                                                    <input type=\"text\" name=\"removeCardFromDeck\" value=\"". $row['card_id']. "\" hidden />
                                                    <button type=\"submit\" class=\"btn btn-danger btn-icon-split\">
                                                        <span class=\"icon text-white-50\">
                                                            <i class=\"fas fa-trash\"></i>
                                                        </span>
                                                    <span class=\"text\">-1</span>
                                                </button>
                                                </form>
                                            </ td>
                                            </tr>";
                        }
              ?>
                                  </tbody>
                                </table>
            
                                <form method="post" action="decks.php"> 
                                    <input type="text" name="addDeckWin" value="1" hidden >
                                    <button type="submit" class="btn btn-success btn-icon-split">
                                        <span class="icon text-white-50">
                                          <i class="fas fa-check"></i>
                                        </span>
                                        <span class="text">Add Deck Win</span>
                                    </button>
                                </form>
                                <form method="post" action="decks.php"> 
                                    <input type="text" name="addDeckLoss" value="1" hidden />
                                    <button type="submit" class="btn btn-danger btn-icon-split">
                                        <span class="icon text-white-50">
                                          <i class="fas fa-trash"></i>
                                        </span>
                                        <span class="text">Add Deck Loss</span>
                                    </button>
                                </form>
                              </div>
                            </div>
             </div>
            </div>
        </div>

      </div>

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Your Website 2019</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
      </div>
    </div>
  </div>

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
