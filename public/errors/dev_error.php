<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Ошибка</title>
  </head>
  <body>
    <div class="container">
      <div class="site-error">

        <h1><?= 'Произошла ошибка' ?></h1>

        <div class="alert alert-danger">
            <b><?= nl2br($errstr) ?></b>
        </div>

        <p>
            <b>Тип: </b> <code><?= $errno ?></code>
        </p>
        <p>
            <b>Файл: </b> <code><?= $errfile ?></code>
        </p>
        <p>
            <b>Строка: </b><code><?= $errline ?></code>
        </p>
        
        <div class="accordion" id="accordionExample">
          <?php for ($i = 0; $i < count($trace); $i++): ?>
          <div class="card">
            <div class="card-header" id="headingOne<?= $i + 1 ?>">
              <h2 class="mb-0">
                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne<?= $i + 1 ?>" aria-expanded="true" aria-controls="collapseOne<?= $i + 1 ?>">
                  Зависимость <?= $i + 1 ?>
                </button>
              </h2>
            </div>

            <div id="collapseOne<?= $i + 1 ?>" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
              <div class="card-body">
                <code><?= $trace[$i] ?></code>
              </div>
            </div>
          </div>
          <?php endfor; ?>
        </div>

      </div>
    </div>
    

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="/js/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</html>