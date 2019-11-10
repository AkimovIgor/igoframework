<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= $meta['description'] ?>">
    <meta name="keywords" content="<?= $meta['keywords'] ?>">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title><?= $meta['title'] ?: 'DEFAULT' ?></title>
  </head>
  <body>
    <div class="container">
      <?php if (isset($menu)): ?>
      <ul class="nav nav-pills">
        <?php foreach ($menu as $item): ?>
        <li class="nav-item">
          <a class="nav-link" href="category/<?= $item['id'] ?>"><?= $item['title'] ?></a>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
      <div class="content">
        <?= $content ?>
      </div>
    </div>
    

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="/js/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="/js/main.js"></script>
    <?php if (!empty($scripts)): ?>
      <?php foreach ($scripts as $script): ?>
        <?= $script ?>
      <?php endforeach; ?>
    <?php endif; ?>
  </body>
</html>