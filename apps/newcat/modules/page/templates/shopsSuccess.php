<h1>Pages List</h1>

<table style="position: relative; top: 0; left: -450px; background: #fff;">
  <thead>
    <tr>
      <th>Id</th>
      <th>getAddress</th>
      <th>getCard</th>
      <th>getCash</th>
      <th>getHouse</th>
      <!-- <th>getLatitude</th> -->
      <!-- <th>getLongitude</th> -->
      <th>getMetro</th>
      <th>getName</th>
      <th>getOutdescription</th>
      <th>getStatus</th>
      <th>getStreet</th>
      <th>getCityId</th>
      <!-- <th>Created at</th> -->
      <!-- <th>Updated at</th> -->
      <!-- <th>getDescription</th> -->
      <th>getWorktime</th>
      <th>getId1c</th>
      <th>getPageId</th>
      <th>getIconmetro</th>
      <th>getPreviewImage</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($pages as $page): ?>
    <tr>
      <td><a href="<?php echo url_for('shops/show?id='.$page->getId()) ?>"><?php echo $page->getId() ?></a></td>
      <td><?php echo $page->getAddress() ?></td>
      <td><?php echo $page->getCard() ?></td>
      <td><?php echo $page->getCash() ?></td>
      <td><?php echo $page->getHouse() ?></td>
      <!-- <td><?php //echo $page->getLatitude() ?></td> -->
      <!-- <td><?php //echo $page->getLongitude() ?></td> -->
      <td><?php echo $page->getMetro() ?></td>
      <td><?php echo $page->getName() ?></td>
      <td><?php echo $page->getOutdescription() ?></td>
      <td><?php echo $page->getStatus() ?></td>
      <td><?php echo $page->getStreet() ?></td>
      <td><?php echo $page->getCityId() ?></td>
      <!-- <td><?php// echo $page->getCreatedAt() ?></td> -->
      <!-- <td><?php// echo $page->getUpdatedAt() ?></td> -->
      <!-- <td><?php// echo $page->getDescription() ?></td> -->
      <td><?php echo $page->getWorktime() ?></td>
      <td><?php echo $page->getId1c() ?></td>
      <td><?php echo $page->getPageId() ?></td>
      <td><?php echo $page->getIconmetro() ?></td>
      <td><?php echo $page->getPreviewImage() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<!-- /uploads/metro/ -->
