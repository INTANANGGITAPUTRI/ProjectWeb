<?php
include "../auth/mw_admin.php";
include '../config/db_connect.php';


$limit = 10; // jumlah data yang ingin ditampilkan per halaman

// ambil halaman saat ini dari parameter GET
$page = $_GET['page'] ?? 1;

// hitung offset dari halaman saat ini
$offset = ($page - 1) * $limit;

$keyword = $_GET['search'] ?? "";
$query = "SELECT products.*, product_categories.name AS category FROM products 
          LEFT JOIN product_categories ON products.product_category_id = product_categories.id";

if ($keyword !== "") {
  $query .= " WHERE products.name LIKE '%$keyword%'";
}

$query .= " LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $query);
$result = mysqli_fetch_all($result, MYSQLI_ASSOC);

// hitung total data untuk menentukan jumlah halaman
$countQuery = "SELECT COUNT(*) FROM products";

if ($keyword !== "") {
  $countQuery .= " WHERE name LIKE '%$keyword%' ";
}

$countResult = mysqli_query($conn, $countQuery);
$total = mysqli_fetch_row($countResult)[0];
$pages = ceil($total / $limit);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Pengaduan</title>
  <?php include '../config/links_cdn.php' ?>
</head>

<body>
  <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/static/js/initTheme.js"></script>

  <div id="app">
    <?php include '../components/admin/sidebar.php' ?>
    <div id="main">
      <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
          <i class="bi bi-justify fs-3"></i>
        </a>
      </header>

      <div class="page-heading">
        <div class="page-title">
          <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
              <h3>Daftar Produk</h3>
            </div>
          </div>
        </div>
      </div>

      <!-- section here -->
      <section class="section">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Daftar Produk</h5>
          </div>
          <div class="card-body">
            <div class="row justify-content-between">
              <div class="col-md-5">
                <form action="" method="get" class="mb-3">
                  <div class="input-group mb-3">
                    <span class="input-group-text text-white" id="basic-addon1"><svg width="24px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path d="M11.625 16.5a1.875 1.875 0 1 0 0-3.75 1.875 1.875 0 0 0 0 3.75Z" />
                        <path fill-rule="evenodd" d="M5.625 1.5H9a3.75 3.75 0 0 1 3.75 3.75v1.875c0 1.036.84 1.875 1.875 1.875H16.5a3.75 3.75 0 0 1 3.75 3.75v7.875c0 1.035-.84 1.875-1.875 1.875H5.625a1.875 1.875 0 0 1-1.875-1.875V3.375c0-1.036.84-1.875 1.875-1.875Zm6 16.5c.66 0 1.277-.19 1.797-.518l1.048 1.048a.75.75 0 0 0 1.06-1.06l-1.047-1.048A3.375 3.375 0 1 0 11.625 18Z" clip-rule="evenodd" />
                        <path d="M14.25 5.25a5.23 5.23 0 0 0-1.279-3.434 9.768 9.768 0 0 1 6.963 6.963A5.23 5.23 0 0 0 16.5 7.5h-1.875a.375.375 0 0 1-.375-.375V5.25Z" />
                      </svg>

                    </span>
                    <input type="text" class="form-control" placeholder="Masukkan nama kue" name="search" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                    <button class="btn btn-primary" type="submit">Cari</button>
                  </div>
                </form>
              </div>
              <div class="col-md-5">
              </div>
            </div>

            <table class="table">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Nama</th>
                  <th scope="col">Kategori</th>
                  <th scope="col">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php $i = 1; ?>
                <?php foreach ($result as $product) : ?>
                  <tr>
                    <th scope="row"><?= ($i + ($limit * ($page - 1))) ?></th>
                    <td><?= $product['name'] ?></td>
                    <td><?= $product['category'] ?></td>
                    <td>
                      <a href="product_detail.php?id=<?= $product['id'] ?>" class="btn btn-primary"><svg width="24px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                          <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                          <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />
                        </svg>
                      </a>
                      <form action="delete_product.php" method="get" class="d-inline">
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                        <button type="submit" class="btn btn-danger"><svg width="24px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                          </svg>
                        </button>
                      </form>
                    </td>
                  </tr>
                  <?php $i++; ?>
                <?php endforeach; ?>
              </tbody>
            </table>
            <div class="text-center d-flex justify-content-center">
              <nav>
                <?php
                $total_products = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM products"))[0];
                $total_pages = ceil($total_products / $limit);
                $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
                ?>
                <ul class="pagination pagination-primary">
                  <li class="page-item <?php echo $current_page == 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $current_page - 1; ?>&limit=<?php echo $limit; ?>">Prev</a>
                  </li>
                  <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                    <li class="page-item <?php echo $current_page == $i ? 'active' : ''; ?>">
                      <a class="page-link" href="?page=<?php echo $i; ?>&limit=<?php echo $limit; ?>"><?php echo $i; ?></a>
                    </li>
                  <?php endfor; ?>
                  <li class="page-item <?php echo $current_page == $total_pages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $current_page + 1; ?>&limit=<?php echo $limit; ?>">Next</a>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>




  <?php include '../config/scripts_cdn.php' ?>


</body>

</html>