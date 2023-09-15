<h1>Servicerequests List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>User</th>
      <th>Fio</th>
      <th>Email</th>
      <th>Created at</th>
      <th>Updated at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($servicerequests as $servicerequest): ?>
    <tr>
      <td><a href="<?php echo url_for('servicerequest/show?id='.$servicerequest->getId()) ?>"><?php echo $servicerequest->getId() ?></a></td>
      <td><?php echo $servicerequest->getUserId() ?></td>
      <td><?php echo $servicerequest->getFio() ?></td>
      <td><?php echo $servicerequest->getEmail() ?></td>
      <td><?php echo $servicerequest->getCreatedAt() ?></td>
      <td><?php echo $servicerequest->getUpdatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('servicerequest/new') ?>">New</a>
