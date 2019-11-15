<?php if (isset($_SESSION['errors'])): ?>
<div class="alert alert-danger" role="alert">
    <?= $_SESSION['errors']; unset($_SESSION['errors']) ?>
</div>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success" role="alert">
    <?= $_SESSION['success']; unset($_SESSION['success']) ?>
</div>
<?php endif; ?>

<h2>Registration</h2>
<form action="/user/register" method="post" style="width: 300px;">
    <div class="form-group">
        <label for="input1">Логин</label>
        <input name="login" type="text" class="form-control" id="input1" placeholder="">
    </div>
    <div class="form-group">
        <label for="input2">Email</label>
        <input name="email" type="text" class="form-control" id="input2" placeholder="">
    </div>
    <div class="form-group">
        <label for="input3">Name</label>
        <input name="name" type="text" class="form-control" id="input3" placeholder="">
    </div>
    <div class="form-group">
        <label for="input4">Password</label>
        <input name="password" type="password" class="form-control" id="input4" placeholder="">
    </div>
    <button class="btn btn-primary" type="submit">Register</button>
</form>