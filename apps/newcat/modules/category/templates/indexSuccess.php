<h1>Categorys List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Name</th>
      <th>Content</th>
      <th>Open</th>
      <th>Is public</th>
      <th>Created at</th>
      <th>Updated at</th>
      <th>Position</th>
      <th>Slug</th>
      <th>Root</th>
      <th>Lft</th>
      <th>Rgt</th>
      <th>Level</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($categorys as $category): ?>
    <tr>
      <td><a href="<?php echo url_for('category/show?id='.$category->getId()) ?>"><?php echo $category->getId() ?></a></td>
      <td><?php echo $category->getName() ?></td>
      <td><?php echo $category->getContent() ?></td>
      <td><?php echo $category->getOpen() ?></td>
      <td><?php echo $category->getIsPublic() ?></td>
      <td><?php echo $category->getCreatedAt() ?></td>
      <td><?php echo $category->getUpdatedAt() ?></td>
      <td><?php echo $category->getPosition() ?></td>
      <td><?php echo $category->getSlug() ?></td>
      <td><?php echo $category->getRootId() ?></td>
      <td><?php echo $category->getLft() ?></td>
      <td><?php echo $category->getRgt() ?></td>
      <td><?php echo $category->getLevel() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('category/new') ?>">New</a>
