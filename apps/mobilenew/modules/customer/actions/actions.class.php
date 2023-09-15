<?php

/**
 * customer actions.
 *
 * @package    test
 * @subpackage customer
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class customerActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $user = $this->getUser();
        if ($user->isAuthenticated()) {
            $q = Doctrine_Manager::getInstance()->getCurrentConnection();

            $timerPage = sfTimerManager::getTimer('Action: Получение заказов');
            $this->orders = $q->execute("select * from orders where customer_id =? order by id DESC", array($user->getGuardUser()->getId()))->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerPage->addTime();






            $timerPage = sfTimerManager::getTimer('Action: Мои данные');
            $this->form = new sfGuardUserMobileForm($user->getGuardUser());

            $this->form->setWidget('password', new sfWidgetFormInput(array('label' => 'Пароль(заполняйте, если хотите изменить)')));
            if ($request->isMethod('post')) {
                $this->form->bind($request->getParameter($this->form->getName()));
                if ($this->form->isValid()) {
                    $user = $this->form->save();
                    $this->getUser()->signIn($user);

                    $this->redirect('@homepage');
                }
            } else {

                $this->form->setDefault('password', '');
            }
            $timerPage->addTime();




            $timerPage = sfTimerManager::getTimer('Action: Мои бонусы');
            $this->bonusCount = $q->execute("select sum(bonus) as bonusCount from bonus where user_id =?", array($user->getGuardUser()->getId()))->fetchAll(Doctrine_Core::FETCH_ASSOC);
            $this->bonus = $q->execute("select * from bonus where user_id =? order by created_at DESC", array($user->getGuardUser()->getId()))->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerPage->addTime();




            $timerPage = sfTimerManager::getTimer('Action: Мои фото');
            $this->userPhotos = $q->execute("select * from photos_user where user_id =?", array($user->getGuardUser()->getId()))->fetchAll(Doctrine_Core::FETCH_UNIQUE);
            $timerPage->addTime();
        } else {
            $this->redirect("@homepage");
        }
    }

    public function executeOrderdetails(sfWebRequest $request) {
        $this->order = OrdersTable::getInstance()->findOneById($request->getParameter('id'));
        $user = $this->getUser()->getGuardUser();
        if ($this->getUser()->isAuthenticated()) {
            if ($this->order)
                if ($this->order->getCustomerId() != $user->getId())
                    unset($this->order);
        }else {
            $this->redirect("@homepage");
        }
    }

    public function executeMydata(sfWebRequest $request) {
        $user = $this->getUser();
        if ($user->isAuthenticated()) {
            $form = new sfGuardUserMobileForm($user->getGuardUser());
            unset($form['algorithm'], $form['salt'], $form['is_active'], $form['is_super_admin'], $form['last_login'], $form['last_ip'], $form['personal_recomendation'], $form['groups_list'], $form['permissions_list'], $form['cart']);

            if (!$request->isMethod('post'))
                $form->setDefault('password', '');
            $form->setWidget('password', new sfWidgetFormInput(array('label' => 'Пароль(заполняйте, если хотите изменить)')));
            // $form->setLabel('password', 'Пароль(заполняйте, если хотите изменить)');
            $this->form = $form;
            $this->update = false;
            if ($request->isMethod('post'))
                $this->processForm($request, $this->form);
        }else {
            $this->redirect("@homepage");
        }
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $page = $form->save();
            $this->update = true;
            $this->redirect('@customer');
        }
    }

}
