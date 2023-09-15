<?php

/**
 * Comments form.
 *
 * @package    Magazin
 * @subpackage form
 * @author     Belfegor
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CommentsCPForm extends CommentsForm {

    protected
            $isAction = true;

    public function configure() {
        unset($this['customer_id'], $this['created_at']);

        $this->setWidget('product_id', new sfWidgetFormDoctrineChoiceSearch(array('model' => $this->getRelatedModelName('Product'), 'query' => Doctrine_Query::create()->select('*')->from('product p')->where("is_public=\"1\"" . ($this->getObject()->getProductId() != "" ? " or id=\"" . $this->getObject()->getProductId() . "\"" : '')), 'add_empty' => true),array("size"=>5)));
        $this->setWidget('article_id', new sfWidgetFormDoctrineChoiceSearch(array('model' => $this->getRelatedModelName('Article'), 'query' => Doctrine_Query::create()->select('*')->from('article a')->where("is_public=\"1\"" . ($this->getObject()->getProductId() != "" ? " or id=\"" . $this->getObject()->getProductId() . "\"" : '')), 'add_empty' => true)));
//$this->setWidget('page_id', new sfWidgetFormDoctrineChoiceSearch(array('model' => $this->getRelatedModelName('Page'), 'query' => Doctrine_Query::create()->select('*')->from('page p')->where("is_public=\"1\"" . ($this->getObject()->getProductId() != "" ? " or id=\"" . $this->getObject()->getProductId() . "\"" : '')), 'add_empty' => true)));
        unset($this['page_id']);


        if ($this->isNew) {
            unset($this['comment_manager'], $this['point'], $this['is_public']);
            //$this->widgetSchema['created_at']->setOption('default', time());
            $this->setWidget('manager_id', new sfWidgetFormInputHidden(array(), array('value' => sfContext::getInstance()->getUser()->getGuardUser()->getId())));
        } else {
            if (sfContext::getInstance()->getUser()->getGuardUser()->getId() == $this->getObject()->getManagerId()) {
                unset($this['comment_manager'], $this['point'], $this['is_public'], $this['manager_id']);
                $this->setWidget('comment_manager', new sfWidgetFormPrintText(array('value' => $this->getObject()->getCommentManager())));
                $this->setWidget('point', new sfWidgetFormPrintText(array('value' => $this->getObject()->getPoint())));
            } elseif ( sfContext::getInstance()->getUser()->hasPermission("All") or sfContext::getInstance()->getUser()->hasPermission("Admin contentportal")) {

                unset($this['manager_id']);
            } else {
                $this->isAction(false);
                unset($this['is_public'],$this['manager_id']);
                $this->setWidget('product_id', new sfWidgetFormPrintText(array('value' => $this->getObject()->getProduct()->getName())));
                $this->setWidget('article_id', new sfWidgetFormPrintText(array('value' => $this->getObject()->getArticle()->getName())));
//$this->setWidget('page_id', new sfWidgetFormPrintText(array('value' => $this->getObject()->getPage()->getName())));
                $this->setWidget('username', new sfWidgetFormPrintText(array('value' => $this->getObject()->getUsername())));
                $this->setWidget('mail', new sfWidgetFormPrintText(array('value' => $this->getObject()->getMail())));
                //$this->setWidget('created_at', new sfWidgetFormPrintText(array('value' => $this->getObject()->getCreatedAt())));
                $this->setWidget('rate_set', new sfWidgetFormPrintText(array('value' => $this->getObject()->getRateSet())));
                $this->setWidget('text', new sfWidgetFormPrintText(array('value' => $this->getObject()->getText())));
                $this->setWidget('answer', new sfWidgetFormPrintText(array('value' => $this->getObject()->getAnswer())));
                $this->setWidget('comment_manager', new sfWidgetFormPrintText(array('value' => $this->getObject()->getCommentManager())));
                $this->setWidget('point', new sfWidgetFormPrintText(array('value' => $this->getObject()->getPoint())));
            }
        }
    }

    public function isAction($params = null) {
        if($params!==null){
            $this->isAction=$params;
        }
        return $this->isAction;
    }

}
