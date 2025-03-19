<?php 
    adminHeader($data);
    adminMenu($data); 
    
?>
    <main class="app-content">
      <div class="page-error tile">
        <h1><i class="fa fa-exclamation-circle"></i> Error <?= $data['code'] ?>: <?= $data['message'] ?></h1>
        <p>La página que ha solicitado no se encuentra.</p>
        <p><a class="btn btn-primary" href="javascript:window.history.back();">Retornar</a></p>
      </div>
    </main>
<?php adminFooter($data); ?>