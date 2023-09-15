<h1>Photos List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Album</th>
      <th>Filename</th>
      <th>Description</th>
      <th>Name</th>
      <th>Is public</th>
      <th>Sort order</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($photos as $photo): ?>
    <tr>
      <td><a href="<?php echo url_for('photo/show?id='.$photo->getId()) ?>"><?php echo $photo->getId() ?></a></td>
      <td><?php echo $photo->getAlbumId() ?></td>
      <td><?php echo $photo->getFilename() ?></td>
      <td><?php echo $photo->getDescription() ?></td>
      <td><?php echo $photo->getName() ?></td>
      <td><?php echo $photo->getIsPublic() ?></td>
      <td><?php echo $photo->getSortOrder() ?></td>
      <td><?php echo $photo->getCreatedAt() ?></td>
      <td><?php echo $photo->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('photo/new') ?>">New</a>
