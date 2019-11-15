
<?php foreach ($posts as $post): ?>
<div class="card bg-light mb-3">
  <div class="card-header">
    <?= $post['title'] ?>    
  </div>  
  <div class="card-body">
    <p class="card-text">
        <?= $post['text'] ?>
    </p>
    <a class="btn btn-primary ajax-btn" data-href="<?= $post['id'] ?>">Button</a>
  </div>
</div>
<?php endforeach; ?>

