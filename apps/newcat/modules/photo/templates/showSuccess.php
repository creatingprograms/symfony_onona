<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $photo->getId() ?></td>
    </tr>
    <tr>
      <th>Album:</th>
      <td><?php echo $photo->getAlbumId() ?></td>
    </tr>
    <tr>
      <th>Filename:</th>
      <td><?php echo $photo->getFilename() ?></td>
    </tr>
    <tr>
      <th>Description:</th>
      <td><?php echo $photo->getDescription() ?></td>
    </tr>
    <tr>
      <th>Name:</th>
      <td><?php echo $photo->getName() ?></td>
    </tr>
    <tr>
      <th>Is public:</th>
      <td><?php echo $photo->getIsPublic() ?></td>
    </tr>
    <tr>
      <th>Sort order:</th>
      <td><?php echo $photo->getSortOrder() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $photo->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $photo->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('photo/edit?id='.$photo->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('photo/index') ?>">List</a>
