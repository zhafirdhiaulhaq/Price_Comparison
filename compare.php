<!-- Aditya Zhafir Dhiaulhaq - 1202184132 - SI4209 -->
<!DOCTYPE html>
<html>
<title>Compare Harga</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">

<body>

  <nav class="navbar navbar-expand-lg navbar-primary bg-primary">
  <span class="navbar-brand mb-0 h1"><b style="color: white;">Price Comparison </b></span>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">

      </ul>
      <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" name="searchdata" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-light my-2 my-sm-0" id="search" value="Search" type="submit">Search</button>
      </form>
    </div>
  </nav>

  <div class="w3-row-padding w3-margin-top">

    <?php
    error_reporting(E_ALL & ~E_NOTICE);
    if (isset($_GET['searchdata'])) {
      $search = $_GET['searchdata'];
      $search = strtolower($search);

      $search = str_replace(" ", "+", $search);
      $web_page_data = file_get_contents("http://www.pricetree.com/search.aspx?q=" . $search);

      $item_list = explode('<div class="items-wrap">', $web_page_data); 

      $i = 1;
      if (sizeof($item_list) < 2) {
        echo '<p style="display:inline-block;width:100%;height:100%; line-height:500px;text-align:center; font-size:30px; color:tomato"><b>Tidak ditemukan, coba masukkan dengan keyword "Iphone 7"</b></p>';
        $i = 5;
      }

      $count = 4;

      for ($i; $i < 5; $i++) {

        $url_link1 = explode('href="', $item_list[$i]);
        $url_link2 = explode('"', $url_link1[1]); 

        $image_link1 = explode('data-original="', $item_list[$i]);
        $image_link2 = explode('"', $image_link1[1]); 

        $title1 = explode('title="', $item_list[$i]);
        $title2 = explode('"', $title1[1]);

        $avaliavle1 = explode('avail-stores">', $item_list[$i]);
        $avaliable = explode('</div>', $avaliavle1[1]);
        if (strcmp($avaliable[0], "Not available") == 0) {
          $count = $count - 1;
          continue;
        }

        $item_title = $title2[0];
        if (strlen($item_title) < 2) {
          continue;
        }
        $item_link = $url_link2[0];
        $item_image_link = $image_link2[0];
        $item_id1 = explode("-", $item_link);
        $item_id = end($item_id1); 
        echo '
    <br>
    <div class="w3-row">
    <div class="w3-col l2 w3-row-padding">
    <div class="w3-card-2" style="background-color:black;color:white;">
    <img src="' . $item_image_link . '" style="width:100%">
    <div class="w3-container">
    <h5>' . $item_title . '</h5>
    </div>
    </div>
    </div>
  ';


        $request = "http://www.pricetree.com/dev/api.ashx?pricetreeId=" . $item_id . "&apikey=7770AD31-382F-4D32-8C36-3743C0271699";
        $response = file_get_contents($request);
        $results = json_decode($response, TRUE);
        echo '
    <div class="w3-col l8">
    <div class="w3-card-2">
      <table class="table table-striped table-Light">
      <thead>
      <tr class="bg-success" style="color:white">
        <th>Nama Toko</th>
        <th>Harga</th>
        <th>URL</th>
      </tr>
      </thead>
    ';
        foreach ($results['data'] as $itemdata) {
          $seller = $itemdata['Seller_Name'];
          $price = $itemdata['Best_Price'];
          $product_link = $itemdata['Uri'];
          echo '

      <tr>
        <td>' . $seller . '</td>
        <td>' . $price . '</td>
        <td><a href="' . $product_link . '" target="_blank">Go to Store</a></td>
      </tr>

      ';
        }
        echo '
      </table>
      </div>
      </div>
      </div>
    ';
      }
      if ($count == 0) {
        
        echo '<p style="display:inline-block;width:100%;height:100%; line-height:500px;text-align:center; font-size:30px; color:tomato"><b>Produk Tidak ditemukan, coba masukkan dengan keyword "Iphone 7"</b></p>';
      }
    } else {
      echo '<p style="display:inline-block;width:100%;height:100%; line-height:500px;text-align:center; font-size:30px; color:darkblue"><b>Langsung coba tulis di pencarian</b></p>';
    }
    ?>

  </div>
  </div>
  </div>

</body>

</html>