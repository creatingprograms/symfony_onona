<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $faqcategory->getId() ?></td>
    </tr>
    <tr>
      <th>Name:</th>
      <td><?php echo $faqcategory->getName() ?></td>
    </tr>
    <tr>
      <th>Content:</th>
      <td><?php echo $faqcategory->getContent() ?></td>
    </tr>
    <tr>
      <th>Is public:</th>
      <td><?php echo $faqcategory->getIsPublic() ?></td>
    </tr>
    <tr>
      <th>Title:</th>
      <td><?php echo $faqcategory->getTitle() ?></td>
    </tr>
    <tr>
      <th>Keywords:</th>
      <td><?php echo $faqcategory->getKeywords() ?></td>
    </tr>
    <tr>
      <th>Description:</th>
      <td><?php echo $faqcategory->getDescription() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $faqcategory->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $faqcategory->getUpdatedAt() ?></td>
    </tr>
    <tr>
      <th>Position:</th>
      <td><?php echo $faqcategory->getPosition() ?></td>
    </tr>
    <tr>
      <th>Slug:</th>
      <td><?php echo $faqcategory->getSlug() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('faqcategory/edit?id='.$faqcategory->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('faqcategory/index') ?>">List</a>
