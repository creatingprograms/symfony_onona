<h1>Notifys List</h1>

<table>
  <thead>
    <tr>
      <th>User</th>
      <th>Email</th>
      <th>Name</th>
      <th>Product</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($notifys as $notify): ?>
    <tr>
      <td><?php echo $notify->getUserId() ?></td>
      <td><a href="<?php echo url_for('notify/show?email='.$notify->getEmail().'&product_id='.$notify->getProductId()) ?>"><?php echo $notify->getEmail() ?></a></td>
      <td><?php echo $notify->getName() ?></td>
      <td><a href="<?php echo url_for('notify/show?email='.$notify->getEmail().'&product_id='.$notify->getProductId()) ?>"><?php echo $notify->getProductId() ?></a></td>
      <td><?php echo $notify->getCreatedAt() ?></td>
      <td><?php echo $notify->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('notify/new') ?>">New</a>
