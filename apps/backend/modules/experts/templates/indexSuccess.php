<h1>Expertss List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Name</th>
      <th>Description</th>
      <th>Photo url</th>
      <th>Position</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($expertss as $experts): ?>
    <tr>
      <td><a href="<?php echo url_for('experts/show?id='.$experts->getId()) ?>"><?php echo $experts->getId() ?></a></td>
      <td><?php echo $experts->getName() ?></td>
      <td><?php echo $experts->getDescription() ?></td>
      <td><?php echo $experts->getPhotoUrl() ?></td>
      <td><?php echo $experts->getPosition() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('experts/new') ?>">New</a>
