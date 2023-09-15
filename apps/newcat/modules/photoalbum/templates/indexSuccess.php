<h1>Photoalbums List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Parent</th>
      <th>Name</th>
      <th>Image</th>
      <th>Content</th>
      <th>Slug</th>
      <th>Is public</th>
      <th>Title</th>
      <th>Keywords</th>
      <th>Description</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($photoalbums as $photoalbum): ?>
    <tr>
      <td><a href="<?php echo url_for('photoalbum/show?id='.$photoalbum->getId()) ?>"><?php echo $photoalbum->getId() ?></a></td>
      <td><?php echo $photoalbum->getParentId() ?></td>
      <td><?php echo $photoalbum->getName() ?></td>
      <td><?php echo $photoalbum->getImage() ?></td>
      <td><?php echo $photoalbum->getContent() ?></td>
      <td><?php echo $photoalbum->getSlug() ?></td>
      <td><?php echo $photoalbum->getIsPublic() ?></td>
      <td><?php echo $photoalbum->getTitle() ?></td>
      <td><?php echo $photoalbum->getKeywords() ?></td>
      <td><?php echo $photoalbum->getDescription() ?></td>
      <td><?php echo $photoalbum->getCreatedAt() ?></td>
      <td><?php echo $photoalbum->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('photoalbum/new') ?>">New</a>
