<div class="content">
  <?php
      echo '<h2>Klientu sarasas<div class="float-right"><a class="btn btn-info" href="/projektas/admin/client_create.php" role="button">Sukurti klienta</a></div></h2>
            <br>
            <table class="table">
                <thead class="thead-dark">
                  <tr>
                      <th scope="col">#</th>
                      <th scope="col">Vardas</th>
                      <th scope="col">Duomenys</th>
                      <th scope="col">Saskaitos israsymas</th>
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


      $total_pages_sql = "SELECT COUNT(*) as count FROM vartotojai where client=1";
      $result = $link->query($total_pages_sql);
      $row = $result->fetch_assoc();
      $total_rows = $row['count'];
      $total_pages = ceil($total_rows / $no_of_records_per_page);

      $sql = "SELECT * FROM vartotojai WHERE client=1 LIMIT $offset, $no_of_records_per_page";
      $result = $link->query($sql);
      while($row = $result->fetch_assoc()) {
          echo '<tr>
            <th scope="row">'.$row['id'].'</th>
            <td>'.$row['username'].'</td>
            <td><a class="btn btn-primary btn-sm" href="#" role="button">Keisti duomenis</a></td>
            <td><a class="btn btn-danger btn-sm" href="admin/bill_create.php?client_id='.$row['id'].'"role="button">Israsyti saskaita</a></td>
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