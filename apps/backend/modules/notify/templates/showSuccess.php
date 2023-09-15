<table>
  <tbody>
    <tr>
      <th>User:</th>
      <td><?php echo $notify->getUserId() ?></td>
    </tr>
    <tr>
      <th>Email:</th>
      <td><?php echo $notify->getEmail() ?></td>
    </tr>
    <tr>
      <th>Name:</th>
      <td><?php echo $notify->getName() ?></td>
    </tr>
    <tr>
      <th>Product:</th>
      <td><?php echo $notify->getProductId() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $notify->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $notify->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('notify/edit?email='.$notify->getEmail().'&product_id='.$notify->getProductId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('notify/index') ?>">List</a>
