<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $experts->getId() ?></td>
    </tr>
    <tr>
      <th>Name:</th>
      <td><?php echo $experts->getName() ?></td>
    </tr>
    <tr>
      <th>Description:</th>
      <td><?php echo $experts->getDescription() ?></td>
    </tr>
    <tr>
      <th>Photo url:</th>
      <td><?php echo $experts->getPhotoUrl() ?></td>
    </tr>
    <tr>
      <th>Position:</th>
      <td><?php echo $experts->getPosition() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('experts/edit?id='.$experts->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('experts/index') ?>">List</a>
