<div class="content">
  <?php
      if (isset($_GET['client'])) {
        $client = $_GET['client'];
        $sql = "SELECT SUM(total_price) as total_price FROM saskaitos WHERE MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE()) AND user_id=$client";
      } else {
        $sql = "SELECT SUM(total_price) as total_price FROM saskaitos WHERE MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE())";
      }
      $result = $link->query($sql);
      $row = $result->fetch_assoc();

      echo '<h2>Sąskaitu sarasas<div class="float-right">Menesio balansas <span class="badge badge-warning">'.$row['total_price'].' &euro;</span></div></h2>
            <br>
            <select style="width: 300px;" class="form-control" name="client" id="client" onchange="clientChange(this)">
            <option value="-1" default>Pasirinkite klienta</option>"';

      $sql = "SELECT * from vartotojai WHERE client=1";
      $result = $link->query($sql);

      while($row = $result->fetch_assoc()) {
        if($client == $row['id']){
          echo '<option value="'.$row['id'].'" selected="True">'.$row['username'].'</option>';
        }
        else{
          echo '<option value="'.$row['id'].'">'.$row['username'].'</option>';
        }
      }
      echo '</select>';

      echo '<br>
            <table class="table">
                <thead class="thead-dark">
                  <tr>
                      <th scope="col">#</th>
                      <th scope="col">Saskaita israse</th>
                      <th scope="col">Saskaitos gavejas</th>
                      <th scope="col">Israsymo data</th>
                      <th scope="col">Suma</th>
                      <th scope="col">Perziureti</th>
                  </tr>
                 </thead>
              <tbody>';
      if (isset($_GET['page'])) {
          $page = $_GET['page'];
      } else {
          $page = 1;
      }

      $no_of_records_per_page = 10;
      $offset = ($page-1) * $no_of_records_per_page;


      $total_pages_sql = "SELECT COUNT(*) as count FROM saskaitos";
      $result = $link->query($total_pages_sql);
      $row = $result->fetch_assoc();
      $total_rows = $row['count'];
      $total_pages = ceil($total_rows / $no_of_records_per_page);


      if (isset($_GET['client'])) {
          $client = $_GET['client'];
          $sql = "SELECT * FROM saskaitos WHERE user_id=$client ORDER BY date desc LIMIT $offset, $no_of_records_per_page";
      } else {
          $sql = "SELECT * FROM saskaitos ORDER BY date desc LIMIT $offset, $no_of_records_per_page";
      }

      $result = $link->query($sql);
      while($row = $result->fetch_assoc()) {

            $user_sql = "SELECT username FROM vartotojai WHERE id=".$row['user_id'];
            $user_result = $link->query($user_sql);
            $user_row = $user_result->fetch_assoc();

            $issuer_sql = "SELECT username FROM vartotojai WHERE id=".$row['user_created'];
            $issuer_result = $link->query($issuer_sql);
            $issuer_row = $issuer_result->fetch_assoc();

            echo '<tr>
            <th scope="row">'.$row['id'].'</th>
            <td>'.$issuer_row['username'].'</td>
            <td>'.$user_row['username'].'</td>
            <td>'.$row['date'].'</td>
            <td>'.$row['total_price'].' &euro;</td>
            <td><a class="btn btn-danger btn-sm" href="client/bill_details.php?bill_id='.$row['id'].'"role="button">Perziureti saskaita</a></td>
          </tr>';
      }

      echo "</tbody></table>";
      $link->close();
  ?>
  <nav>
    <ul class="pagination justify-content-center">
      <li class="page-item"><a class="page-link" href="?page=1">First</a></li>
      <li class="<?php if($page <= 1){ echo 'disabled'; } ?> page-item">
          <a class="page-link" href="<?php if($page <= 1){ echo '#'; } else { echo "?page=".($page - 1); } ?>">Prev</a>
      </li>
      <li class="<?php if($page >= $total_pages){ echo 'disabled'; } ?> page-item">
          <a class="page-link" href="<?php if($page >= $total_pages){ echo '#'; } else { echo "?page=".($page + 1); } ?>">Next</a>
      </li>
      <li class="page-item"><a class="page-link" href="?page=<?php echo $total_pages; ?>">Last</a></li>
    </ul>
  </nav>
</div>

<script type="text/javascript">
  function clientChange(client) {
    if(client.value == -1){
      window.location.href = "index.php";
    }
    else{
      location.href = location.origin + location.pathname + '?client=' + client.value;
    }
  }
</script>