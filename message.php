<?php

if (isset($_SESSION['type']) && $_SESSION['type'] == 'success'){
  $typeCss = 'alert alert-success alert-dismissible fade show';
} elseif (isset($_SESSION['type']) && $_SESSION['type'] == 'error'){
  $typeCss = 'alert alert-danger alert-dismissible fade show';
}

if (isset($_SESSION['message'])) :
  
?>

<div class="<?=$typeCss?>" role="alert">
  <?php echo $_SESSION['message'];?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<?php

unset($_SESSION['message']);
endif;

?>
