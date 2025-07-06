<?= adminHeader($data) ?>
<?= adminMenu($data) ?>
<?php //filelang(Setlanguage,"general") 
?>
<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-dashboard"></i> <?= $data['page_title'] ?></h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>/dashboard">Dashboard</a></li>
    </ul>
  </div>


  








</main>
<?= adminFooter($data) ?>