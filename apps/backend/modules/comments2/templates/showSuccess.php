<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $comments->getId() ?></td>
    </tr>
    <tr>
      <th>Text:</th>
      <td><?php echo $comments->getText() ?></td>
    </tr>
    <tr>
      <th>Product:</th>
      <td><?php echo $comments->getProductId() ?></td>
    </tr>
    <tr>
      <th>Page:</th>
      <td><?php echo $comments->getPageId() ?></td>
    </tr>
    <tr>
      <th>Article:</th>
      <td><?php echo $comments->getArticleId() ?></td>
    </tr>
    <tr>
      <th>Compare:</th>
      <td><?php echo $comments->getCompareId() ?></td>
    </tr>
    <tr>
      <th>Customer:</th>
      <td><?php echo $comments->getCustomerId() ?></td>
    </tr>
    <tr>
      <th>Is public:</th>
      <td><?php echo $comments->getIsPublic() ?></td>
    </tr>
    <tr>
      <th>Username:</th>
      <td><?php echo $comments->getUsername() ?></td>
    </tr>
    <tr>
      <th>Mail:</th>
      <td><?php echo $comments->getMail() ?></td>
    </tr>
    <tr>
      <th>Answer:</th>
      <td><?php echo $comments->getAnswer() ?></td>
    </tr>
    <tr>
      <th>Rate plus:</th>
      <td><?php echo $comments->getRatePlus() ?></td>
    </tr>
    <tr>
      <th>Rate minus:</th>
      <td><?php echo $comments->getRateMinus() ?></td>
    </tr>
    <tr>
      <th>Rate set:</th>
      <td><?php echo $comments->getRateSet() ?></td>
    </tr>
    <tr>
      <th>Point:</th>
      <td><?php echo $comments->getPoint() ?></td>
    </tr>
    <tr>
      <th>Comment manager:</th>
      <td><?php echo $comments->getCommentManager() ?></td>
    </tr>
    <tr>
      <th>Manager:</th>
      <td><?php echo $comments->getManagerId() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $comments->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $comments->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('comments2/edit?id='.$comments->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('comments2/index') ?>">List</a>
