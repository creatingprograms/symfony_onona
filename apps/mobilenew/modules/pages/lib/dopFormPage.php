<?php

class dopFormPage {

    static function getFormSexolog($errorCap) {

        mb_internal_encoding('UTF-8');
        $html = <<<HERE
    <form action="/vopros-seksologu" method="post" width="100%" id="support">
<table calss="noBorder" style="width:100%;" class="tableRegister">
                <tbody>
                    <tr>
  <th><label for="name">Ваше имя/псевдоним:*</label></th>
  <td><input type="text" style="width: 400px" name="name" value="" id="name"></td>
</tr>
                </tbody>

                
                <tbody>
                    <tr>
  <th><label for="email">Ваш e-mail:*</label></th>
  <td><input type="text" style="width: 400px" name="email" value="" id="email"></td>
</tr>
                </tbody>

                
                <tbody>
                    <tr>
  <th><label for="sub">Тема сообщения:*</label></th>
  <td><input type="text" style="width: 400px" name="sub" value="" id="sub"></td>
</tr>
                </tbody>

                
                <tbody>
                    <tr>
  <th><label for="msg">Сообщение:*</label></th>
  <td><textarea style="width: 400px; height: 100px;" name="msg" value="" id="msg"></textarea></td>
</tr>
                </tbody>

                
                <tbody>
                    <tr>
  <th><label for="code">Укажите код:*</label></th>
HERE;
        $html.='<td><img border="0" style="float: left; margin-right: 10px;" src="/captcha/supportcaptcha.php?' . session_name() . '=' . session_id() . '"><input type="text" style="width: 270px" name="code" value="" id="code"><br>';

        if ($errorCap)
            $html.="Ошибка. Попробуйте ещё раз.";
        $html.= <<<HERE
        </td>
        </tr>
        </tbody>

        </table>
        * - поля, отмеченные * обязательны для заполнения.<br>
        <center><a style = "margin-left: 300px;" class = "red-btn colorWhite" href = "#" onclick = "$('#support').submit(); return false;"><span style = "width: 120px;">Отправить</span></a></center>
        </form>
HERE;
        return $html;
    }

    static function getFormVacancy($errorCap) {

        mb_internal_encoding('UTF-8');
        $html = <<<HERE
    <form action="/creative_vacancy" method="post" width="100%" id="support" enctype="multipart/form-data">
<table calss="noBorder" style="width:100%;" class="tableRegister">
                <tbody>
                    <tr>
  <th><label for="name">Ваше имя/псевдоним:*</label></th>
  <td><input type="text" style="width: 400px" name="name" value="" id="name"></td>
</tr>
                </tbody>

                
                <tbody>
                    <tr>
  <th><label for="email">Ваш e-mail:*</label></th>
  <td><input type="text" style="width: 400px" name="email" value="" id="email"></td>
</tr>
                </tbody>

                
                <tbody>
                    <tr>
  <th><label for="email">Ваш телефон:*</label></th>
  <td><input type="text" style="width: 400px" name="phone" value="" id="phone"></td>
</tr>
                </tbody>

                
                <tbody>
                    <tr>
  <th><label for="sub">Тема сообщения:*</label></th>
  <td><input type="text" style="width: 400px" name="sub" value="" id="sub"></td>
</tr>
                </tbody>

                
                <tbody>
                    <tr>
  <th><label for="msg">Сообщение:*</label></th>
  <td><textarea style="width: 400px; height: 100px;" name="msg" value="" id="msg"></textarea></td>
</tr>
                </tbody>


                <tbody>
                    <tr>
  <th><label for="msg">Фотография 1:</label></th>
  <td><input type="file" name="files[]" /></td>
</tr>
                </tbody>


                <tbody>
                    <tr>
  <th><label for="msg">Фотография 2:</label></th>
  <td><input type="file" name="files[]" /></td>
</tr>
                </tbody>


                <tbody>
                    <tr>
  <th><label for="msg">Фотография 3:</label></th>
  <td><input type="file" name="files[]" /></td>
</tr>
                </tbody>
                
                <tbody>
                    <tr>
  <th><label for="code">Укажите код:*</label></th>
HERE;
        $html.='<td><img border="0" style="float: left; margin-right: 10px;" src="/captcha/supportcaptcha.php?' . session_name() . '=' . session_id() . '"><input type="text" style="width: 270px" name="code" value="" id="code"><br>';

        if ($errorCap)
            $html.="Ошибка. Попробуйте ещё раз.";
        $html.= <<<HERE
        </td>
        </tr>
        </tbody>

        </table>
        * - поля, отмеченные * обязательны для заполнения.<br>
        <center><a style = "margin-left: 300px;" class = "red-btn colorWhite" href = "#" onclick = "$('#support').submit(); return false;"><span style = "width: 120px;">Отправить</span></a></center>
        </form>
HERE;
        return $html;
    }

    static function getFormKonsultSexolog($errorCap) {

        mb_internal_encoding('UTF-8');
        $html = <<<HERE
    <form action="/konsultatsia_seksologa" method="post" width="100%" id="support">
<table calss="noBorder" style="width:100%;" class="tableRegister">
                <tbody>
                    <tr>
  <th><label for="name">Ваше имя/псевдоним:*</label></th>
  <td><input type="text" style="width: 400px" name="name" value="" id="name"></td>
</tr>
                </tbody>

                
                <tbody>
                    <tr>
  <th><label for="email">Ваш e-mail:*</label></th>
  <td><input type="text" style="width: 400px" name="email" value="" id="email"></td>
</tr>
                </tbody>

                
                <tbody>
                    <tr>
  <th><label for="sub">Тема сообщения:</label></th>
  <td><input type="text" style="width: 400px" name="sub" value="" id="sub"></td>
</tr>
                </tbody>

                
                <tbody>
                    <tr>
  <th><label for="msg">Сообщение:*</label></th>
  <td><textarea style="width: 400px; height: 100px;" name="msg" value="" id="msg"></textarea></td>
</tr>
                </tbody>

                
                <tbody>
                    <tr>
  <th><label for="code">Укажите код:*</label></th>
HERE;
        $html.='<td><img border="0" style="float: left; margin-right: 10px;" src="/captcha/supportcaptcha.php?' . session_name() . '=' . session_id() . '"><input type="text" style="width: 270px" name="code" value="" id="code"><br>';

        if ($errorCap)
            $html.="Ошибка. Попробуйте ещё раз.";
        $html.= <<<HERE
        </td>
        </tr>
        </tbody>

        </table>
        * - поля, отмеченные * обязательны для заполнения.<br>
        <center><a style = "margin-left: 300px;" class = "red-btn colorWhite" href = "#" onclick = "$('#support').submit(); return false;"><span style = "width: 120px;">Отправить</span></a></center>
        </form>
HERE;
        return $html;
    }

    static function getFormTreningi($errorCap) {

        mb_internal_encoding('UTF-8');
        $html = '
    <form action="/treningi-shig#запись на тренинг" method="post" width="100%" id="support">
<table calss="noBorder" style="width:100%;" class="tableRegister">
                <tbody>
                    <tr>
  <th><label for="name">Ваше имя/псевдоним:*</label></th>
  <td><input type="text" style="width: 400px" name="name" id="name" value="' . @$_POST['name'] . '"></td>
</tr>
                </tbody>

                
                <tbody>
                    <tr>
  <th><label for="email">Ваш e-mail:*</label></th>
  <td><input type="text" style="width: 400px" name="email" id="email" value="' . @$_POST['email'] . '"></td>
</tr>
                </tbody>

                
                <tbody>
                    <tr>
  <th><label for="email">Ваш телефон:*</label></th>
  <td><input type="text" style="width: 400px" name="phone" id="phone" value="' . @$_POST['phone'] . '"></td>
</tr>
                </tbody>

                
                <tbody>
                    <tr>
  <th><label for="sub">Тема сообщения:</label></th>
  <td><input type="text" style="width: 400px" name="sub" id="sub" value="' . @$_POST['sub'] . '"></td>
</tr>
                </tbody>

                
                <tbody>
                    <tr>
  <th><label for="msg">Сообщение:*</label></th>
  <td><textarea style="width: 400px; height: 100px;" name="msg" value="" id="msg">' . @$_POST['msg'] . '</textarea></td>
</tr>
                </tbody>

                
                <tbody>
                    <tr>
  <th><label for="code">Укажите код:*</label></th>
';
        $html.='<td><img border="0" style="float: left; margin-right: 10px;" src="/captcha/supportcaptcha.php?' . session_name() . '=' . session_id() . '"><input type="text" style="width: 270px" name="code" value="" id="code"><br>';

        if ($errorCap)
            $html.="Ошибка. Попробуйте ещё раз.";
        $html.= <<<HERE
        </td>
        </tr>
        </tbody>

        </table>
        * - поля, отмеченные * обязательны для заполнения.<br>
        <center><a style = "margin-left: 300px;" class = "red-btn colorWhite" href = "#" onclick = "$('#support').submit(); return false;"><span style = "width: 120px;">Отправить</span></a></center>
        </form><div style="clear: both;"></div>
HERE;
        return $html;
    }

    static function getFormRegformvideo($errorCap) {

        mb_internal_encoding('UTF-8');
        $html = '
    <form action="/forma-registracii-dlya-video" method="post" width="100%" id="support">
<table calss="noBorder" style="width:100%;" class="tableRegister">
                <tbody>
                    <tr>
  <th><label for="name">Ваше имя/псевдоним:*</label></th>
  <td><input type="text" style="width: 400px" name="name" id="name" value="' . @$_POST['name'] . '"></td>
</tr>
                </tbody>

                
                <tbody>
                    <tr>
  <th><label for="email">Ваш e-mail:*</label></th>
  <td><input type="text" style="width: 400px" name="email" id="email" value="' . @$_POST['email'] . '"></td>
</tr>
                </tbody>
                
                <tbody>
                    <tr>
  <th><label for="code">Укажите код:*</label></th>
';
        $html.='<td><img border="0" style="float: left; margin-right: 10px;" src="/captcha/supportcaptcha.php?' . session_name() . '=' . session_id() . '"><input type="text" style="width: 270px" name="code" value="" id="code"><br>';

        if ($errorCap)
            $html.="Ошибка. Попробуйте ещё раз.";
        $html.= <<<HERE
        </td>
        </tr>
        </tbody>

        </table>
        * - поля, отмеченные * обязательны для заполнения.<br>
        <center><a style = "margin-left: 300px;" class = "red-btn colorWhite" href = "#" onclick = "$('#support').submit(); return false;"><span style = "width: 120px;">Отправить</span></a></center>
        </form><div style="clear: both;"></div>
HERE;
        return $html;
    }

    public static function rus2translit($string) {
        $converter = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v',
            'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
            'ь' => '', 'ы' => 'y', 'ъ' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
            'А' => 'A', 'Б' => 'B', 'В' => 'V',
            'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
            'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
            'И' => 'I', 'Й' => 'Y', 'К' => 'K',
            'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U',
            'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
            'Ь' => '', 'Ы' => 'Y', 'Ъ' => '',
            'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
        );
        return strtr($string, $converter);
    }

    public function handlerDopForm(sfWebRequest $request) {
        if ($request->getParameter('slug') == "vopros-seksologu" and $request->isMethod(sfRequest::POST)) {
            $captcha = CaptchaTable::getInstance()->createQuery()->where("subid='" . session_id() . "' and type='sup'")->fetchOne();
            if ($request->getParameter('code') == $captcha->getVal()) {
                /* $transport = Swift_SmtpTransport::newInstance(csSettings::get("smtp_address"), csSettings::get("smtp_port"))
                  ->setUsername(csSettings::get("smtp_user"))
                  ->setPassword(csSettings::get("smtp_pass"));
                  $mailer = Swift_Mailer::newInstance($transport); */
                if (dopFuncPage::is_email($_POST['email'])) {
                    //print_r($arrayemailsfastorder);exit;
                    $emailsfastorder = explode(";", csSettings::get("emailsvoprsexolog"));
                    foreach ($emailsfastorder as $email) {
                        $arrayemailsfastorder[$email] = "";
                    }
                    /* $message = Swift_Message::newInstance('Поступил вопрос сексологу', "Здравствуйте!<br>
                      Имя: " . $_POST['name'] . "<br>
                      Email: " . $_POST['email'] . "<br>
                      Тема сообщения: " . $_POST['sub'] . "<br>
                      Сообщение: " . nl2br($_POST['msg']) . "<br>", 'text/html', 'utf-8')
                      ->setFrom(array(csSettings::get("smtp_user") => 'Почтовый робот сайта OnOna.Ru'))
                      ->setTo($arrayemailsfastorder);

                      $numSent = $mailer->send($message); */

                    $message = Swift_Message::newInstance('Поступил вопрос сексологу', "Здравствуйте!<br>
		Имя: " . $_POST['name'] . "<br>
		Email: " . $_POST['email'] . "<br>
		Тема сообщения: " . $_POST['sub'] . "<br>
		Сообщение: " . nl2br($_POST['msg']) . "<br>", 'text/html', 'utf-8')
                            ->setFrom(array(csSettings::get("smtp_user") => 'Почтовый робот сайта OnOna.Ru'))
                            ->setTo($arrayemailsfastorder)
                            ->setSubject('Поступил вопрос сексологу')
                            ->setBody("Здравствуйте!<br>
		Имя: " . $_POST['name'] . "<br>
		Email: " . $_POST['email'] . "<br>
		Тема сообщения: " . $_POST['sub'] . "<br>
		Сообщение: " . nl2br($_POST['msg']) . "<br>")
                            ->setContentType('text/html')
                    ;
                    $this->getMailer()->send($message);
                }
                $this->errorCap = "";
                $this->page = Doctrine_Core::getTable('Page')->findOneBySlug(array("vopros-seksologu-2"));
                $this->forward404Unless($this->page);
            } else {
                $this->errorCap = "Ошибка. Попробуйте ещё раз.";
            }
        }
        if ($request->getParameter('slug') == "creative_vacancy" and $request->isMethod(sfRequest::POST)) {
            $captcha = CaptchaTable::getInstance()->createQuery()->where("subid='" . session_id() . "' and type='sup'")->fetchOne();
            if ($request->getParameter('code') == $captcha->getVal()) {

                //print_r($_FILES);exit;
                if (dopFuncPage::is_email($_POST['email'])) {
                    $imgTag = "";
                    $images_arr = array();
                    foreach ($_FILES['files']['name'] as $key => $val) {
                        //upload and stored images
                        $target_dir = "/var/www/ononaru/data/www/onona.ru/uploads/vacancy/";
                        $target_file = $target_dir . dopFormPage::rus2translit($_FILES['files']['name'][$key]);
                        if (move_uploaded_file($_FILES['files']['tmp_name'][$key], $target_file)) {
                            $images_arr[] = $target_file;
                            $imgTag.="<img src='https://onona.ru/uploads/vacancy/" . dopFormPage::rus2translit($_FILES['files']['name'][$key]) . "'>";
                        }
                    }
                    //exit;
                    $message = Swift_Message::newInstance('Поступило обращение по вакансии', "Здравствуйте!<br>
		Имя: " . $_POST['name'] . "<br>
		Email: " . $_POST['email'] . "<br>
		Телефон: " . $_POST['phone'] . "<br>
		Тема сообщения: " . $_POST['sub'] . "<br>
		Сообщение: " . nl2br($_POST['msg']) . "<br>" . $imgTag, 'text/html', 'utf-8')
                            ->setFrom(array(csSettings::get("smtp_user") => 'Почтовый робот сайта OnOna.Ru'))
                            ->setTo("svs@onona.ru")
                            ->setSubject('Поступило обращение по вакансии')
                            ->setBody("Здравствуйте!<br>
		Имя: " . $_POST['name'] . "<br>
		Email: " . $_POST['email'] . "<br>
		Телефон: " . $_POST['phone'] . "<br>
		Тема сообщения: " . $_POST['sub'] . "<br>
		Сообщение: " . nl2br($_POST['msg']) . "<br>" . $imgTag)
                            ->setContentType('text/html')
                    ;
                    $this->getMailer()->send($message);
                }
                //exit;
                $this->errorCap = "";
                $this->page = Doctrine_Core::getTable('Page')->findOneBySlug(array("soobschenie-otpravleno"));
                $this->forward404Unless($this->page);
            } else {
                $this->errorCap = "Ошибка. Попробуйте ещё раз.";
            }
        }
        if ($request->getParameter('slug') == "konsultatsia_seksologa" and $request->isMethod(sfRequest::POST)) {
            $captcha = CaptchaTable::getInstance()->createQuery()->where("subid='" . session_id() . "' and type='sup'")->fetchOne();
            if ($request->getParameter('code') == $captcha->getVal()) {
                /* $transport = Swift_SmtpTransport::newInstance(csSettings::get("smtp_address"), csSettings::get("smtp_port"))
                  ->setUsername(csSettings::get("smtp_user"))
                  ->setPassword(csSettings::get("smtp_pass"));
                  $mailer = Swift_Mailer::newInstance($transport); */
                if (dopFuncPage::is_email($_POST['email'])) {
                    //print_r($arrayemailsfastorder);exit;
                    $emailsfastorder = explode(";", csSettings::get("emailskonssex"));
                    foreach ($emailsfastorder as $email) {
                        $arrayemailsfastorder[$email] = "";
                    }
                    /* $message = Swift_Message::newInstance('Поступил запрос консултации сексологу', "Здравствуйте!<br>
                      Имя: " . $_POST['name'] . "<br>
                      Email: " . $_POST['email'] . "<br>
                      Тема сообщения: " . $_POST['sub'] . "<br>
                      Сообщение: " . nl2br($_POST['msg']) . "<br>", 'text/html', 'utf-8')
                      ->setFrom(array(csSettings::get("smtp_user") => 'Почтовый робот сайта OnOna.Ru'))
                      ->setTo($arrayemailsfastorder);

                      $numSent = $mailer->send($message); */
                    $message = Swift_Message::newInstance('Поступил запрос консултации сексологу', "Здравствуйте!<br>
		Имя: " . $_POST['name'] . "<br>
		Email: " . $_POST['email'] . "<br>
		Тема сообщения: " . $_POST['sub'] . "<br>
		Сообщение: " . nl2br($_POST['msg']) . "<br>", 'text/html', 'utf-8')
                            ->setFrom(array(csSettings::get("smtp_user") => 'Почтовый робот сайта OnOna.Ru'))
                            ->setTo($arrayemailsfastorder)
                            ->setSubject('Поступил запрос консултации сексологу')
                            ->setBody("Здравствуйте!<br>
		Имя: " . $_POST['name'] . "<br>
		Email: " . $_POST['email'] . "<br>
		Тема сообщения: " . $_POST['sub'] . "<br>
		Сообщение: " . nl2br($_POST['msg']) . "<br>")
                            ->setContentType('text/html')
                    ;
                    $this->getMailer()->send($message);
                }
                $this->errorCap = "";
                $this->page = Doctrine_Core::getTable('Page')->findOneBySlug(array("konsultatsia_seksologa-2"));
                $this->forward404Unless($this->page);
            } else {
                $this->errorCap = "Ошибка. Попробуйте ещё раз.";
            }
        }

        if ($request->getParameter('slug') == "treningi-shig" and $request->isMethod(sfRequest::POST)) {
            $captcha = CaptchaTable::getInstance()->createQuery()->where("subid='" . session_id() . "' and type='sup'")->fetchOne();
            if ($request->getParameter('code') == $captcha->getVal()) {
                /* $transport = Swift_SmtpTransport::newInstance(csSettings::get("smtp_address"), csSettings::get("smtp_port"))
                  ->setUsername(csSettings::get("smtp_user"))
                  ->setPassword(csSettings::get("smtp_pass"));
                  //echo csSettings::get("smtp_pass");exit;
                  $mailer = Swift_Mailer::newInstance($transport); */
                if (dopFuncPage::is_email($_POST['email'])) {

                    //print_r($arrayemailsfastorder);exit;
                    $emailsfastorder = explode(";", csSettings::get("emailstrening"));
                    foreach ($emailsfastorder as $email) {
                        $arrayemailsfastorder[$email] = "";
                    }
                    //$arrayemailsfastorder=array();
                    //$arrayemailsfastorder['smakemy@gmail.com']="";
                    //echo "ewe";exit;
                    $message = Swift_Message::newInstance('Поступил запрос тренинга ШИГ', "Здравствуйте!<br>
		Имя: " . $_POST['name'] . "<br>
		Email: " . $_POST['email'] . "<br>
		Телефон: " . $_POST['phone'] . "<br>
		Тема сообщения: " . $_POST['sub'] . "<br>
		Сообщение: " . nl2br($_POST['msg']) . "<br>", 'text/html', 'utf-8')
                            ->setFrom(array(csSettings::get("smtp_user") => 'Почтовый робот сайта OnOna.Ru'))
                            ->setTo($arrayemailsfastorder);

                    //$numSent = $mailer->send($message);
                    $this->getMailer()->send($message);
                }
                $this->errorCap = "";
                $this->page = Doctrine_Core::getTable('Page')->findOneBySlug(array("treningi-shig-2"));
                $this->forward404Unless($this->page);
            } else {
                $this->errorCap = "Ошибка. Попробуйте ещё раз.";
            }
        }

        if ($request->getParameter('slug') == "forma-registracii-dlya-video" and $request->isMethod(sfRequest::POST)) {
            $captcha = CaptchaTable::getInstance()->createQuery()->where("subid='" . session_id() . "' and type='sup'")->fetchOne();
            if ($request->getParameter('code') == $captcha->getVal()) {
                /*  $transport = Swift_SmtpTransport::newInstance(csSettings::get("smtp_address"), csSettings::get("smtp_port"))
                  ->setUsername(csSettings::get("smtp_user"))
                  ->setPassword(csSettings::get("smtp_pass"));
                  $mailer = Swift_Mailer::newInstance($transport); */
                if (dopFuncPage::is_email($_POST['email'])) {
                    $regvideo = new Regformvideo();
                    $regvideo->setName($_POST['name']);
                    $regvideo->setMail($_POST['email']);
                    $regvideo->save();

                    $message = Swift_Message::newInstance('Запрос ссылки на видео', csSettings::get("emailvideo"), 'text/html', 'utf-8')
                            ->setFrom(array(csSettings::get("smtp_user") => 'Почтовый робот сайта OnOna.Ru'))
                            ->setTo($_POST['email']);

                    //$numSent = $mailer->send($message);

                    $this->getMailer()->send($message);
                }
                $this->errorCap = "";
                $this->page = Doctrine_Core::getTable('Page')->findOneById(array(145));
                $this->forward404Unless($this->page);
            } else {
                $this->errorCap = "Ошибка. Попробуйте ещё раз.";
            }
        }
        $returnRes['page'] = $this->page;
        $returnRes['errorCap'] = $this->errorCap;
        return $returnRes;
    }

}

?>
