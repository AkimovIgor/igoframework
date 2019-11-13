<?php if ($dropdown): ?>
    <li class="dropdown-item <?php if (isset($category['childs'])): ?>dropdown<?php endif; ?>">
  <a href="?id=<?= $id ?>"<?php if (isset($category['childs'])): ?> class="dropdown-toggle" id="navbarDropdown<?= $id ?>" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"<?php endif; ?>><?= $category['title'] ?></a>
        <?php if (isset($category['childs'])): ?>
        <ul class="dropdown-menu" aria-labelledby="navbarDropdown<?= $id ?>">
            <?= $this->getMenuHtml($category['childs'], true) ?>
        </ul>
    </li>
    <?php endif ?> 
<?php else: ?>
<li class="nav-item <?php if (isset($category['childs'])): ?>dropdown<?php endif; ?>">
    <a class="nav-link <?php if (isset($category['childs'])): ?>dropdown-toggle<?php endif; ?>" href="?id=<?= $id ?>" <?php if (isset($category['childs'])): ?>id="navbarDropdown<?= $id ?>" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"<?php endif; ?>><?= $category['title'] ?></a>
    <?php if (isset($category['childs'])): ?>
        <ul class="dropdown-menu" aria-labelledby="navbarDropdown<?= $id ?>">
            <?= $this->getMenuHtml($category['childs'], true) ?>
        </ul>
    <?php endif ?> 
  </li>
<?php endif; ?> 
  


