<h1>Faqcategorys List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Name</th>
      <th>Content</th>
      <th>Is public</th>
      <th>Title</th>
      <th>Keywords</th>
      <th>Description</th>
      <th>Created at</th>
      <th>Updated at</th>
      <th>Position</th>
      <th>Slug</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($faqcategorys as $faqcategory): ?>
    <tr>
      <td><a href="<?php echo url_for('faqcategory/show?id='.$faqcategory->getId()) ?>"><?php echo $faqcategory->getId() ?></a></td>
      <td><?php echo $faqcategory->getName() ?></td>
      <td><?php echo $faqcategory->getContent() ?></td>
      <td><?php echo $faqcategory->getIsPublic() ?></td>
      <td><?php echo $faqcategory->getTitle() ?></td>
      <td><?php echo $faqcategory->getKeywords() ?></td>
      <td><?php echo $faqcategory->getDescription() ?></td>
      <td><?php echo $faqcategory->getCreatedAt() ?></td>
      <td><?php echo $faqcategory->getUpdatedAt() ?></td>
      <td><?php echo $faqcategory->getPosition() ?></td>
      <td><?php echo $faqcategory->getSlug() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('faqcategory/new') ?>">New</a>
