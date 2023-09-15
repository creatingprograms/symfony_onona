      <div style="float: left;color: #c3060e;padding-top: 5px; margin-right: 10px;"><input type="checkbox" id="checkboxNotify">Я хочу подписаться на уведомления о новых поступлениях товаров в этом разделе:</div>
                <div style="float: left;" class="silverButtonAddToNotification" onClick='if (!$("#checkboxNotify").prop("checked")) {
                            return false;
                        }
                        $(".NotificationCategory").load("/addnotification", {categoryAddNotification: <?= $sf_request->getParameter('catid') ?>});'></div>
