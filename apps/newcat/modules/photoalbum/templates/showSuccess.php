<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $photoalbum->getId() ?></td>
    </tr>
    <tr>
      <th>Parent:</th>
      <td><?php echo $photoalbum->getParentId() ?></td>
    </tr>
    <tr>
      <th>Name:</th>
      <td><?php echo $photoalbum->getName() ?></td>
    </tr>
    <tr>
      <th>Image:</th>
      <td><?php echo $photoalbum->getImage() ?></td>
    </tr>
    <tr>
      <th>Content:</th>
      <td><?php echo $photoalbum->getContent() ?></td>
    </tr>
    <tr>
      <th>Slug:</th>
      <td><?php echo $photoalbum->getSlug() ?></td>
    </tr>
    <tr>
      <th>Is public:</th>
      <td><?php echo $photoalbum->getIsPublic() ?></td>
    </tr>
    <tr>
      <th>Title:</th>
      <td><?php echo $photoalbum->getTitle() ?></td>
    </tr>
    <tr>
      <th>Keywords:</th>
      <td><?php echo $photoalbum->getKeywords() ?></td>
    </tr>
    <tr>
      <th>Description:</th>
      <td><?php echo $photoalbum->getDescription() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $photoalbum->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $photoalbum->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('photoalbum/edit?id='.$photoalbum->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('photoalbum/index') ?>">List</a>
