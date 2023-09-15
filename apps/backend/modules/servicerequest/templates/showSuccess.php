<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $servicerequest->getId() ?></td>
    </tr>
    <tr>
      <th>User:</th>
      <td><?php echo $servicerequest->getUserId() ?></td>
    </tr>
    <tr>
      <th>Fio:</th>
      <td><?php echo $servicerequest->getFio() ?></td>
    </tr>
    <tr>
      <th>Email:</th>
      <td><?php echo $servicerequest->getEmail() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $servicerequest->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $servicerequest->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('servicerequest/edit?id='.$servicerequest->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('servicerequest/index') ?>">List</a>
