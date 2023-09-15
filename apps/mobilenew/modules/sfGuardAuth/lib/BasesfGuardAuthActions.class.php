<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: BasesfGuardAuthActions.class.php 23800 2009-11-11 23:30:50Z Kris.Wallsmith $
 */
class BasesfGuardAuthActions extends sfActions {

    public function executeSignin($request) {

        $user = $this->getUser();
        if ($user->isAuthenticated()) {
            return $this->redirect('@homepage');
        }
        if ($this->getUser()->getAttribute('entercount') > 5 and $this->getUser()->getAttribute('enterlasttime') != "" and ($this->getUser()->getAttribute('enterlasttime') + 300) > time()) {
            return $this->redirect('/forgotpassword');
        } elseif ($this->getUser()->getAttribute('entercount') > 5 and $this->getUser()->getAttribute('enterlasttime') != "") {
            $this->getUser()->setAttribute("enterlasttime", "");
            $this->getUser()->setAttribute("entercount", 0);
        }

        $class = sfConfig::get('app_sf_guard_plugin_signin_form', 'sfGuardFormSignin');
        $this->form = new $class();
        if ($user->getReferer($request->getReferer()) != "https://onona.ru/guard/login" and $user->getReferer($request->getReferer()) != "https://onona.ru/guard/login" and $user->getReferer($request->getReferer()) != "http://www.onona.ru/guard/login") {
            $user->setAttribute('returnURL', $user->getReferer($request->getReferer()));
        }
        //$user->setAttribute('returnURL', $user->getReferer(($request->getReferer())!="https://onona.ru/guard/login" && $request->getReferer())!="http://www.onona.ru/guard/login")?$user->getReferer($request->getReferer()):$user->getAttribute('returnURL'));
        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter('signin'));

            if ($this->form->isValid()) {
                $values = $this->form->getValues();

                $this->getUser()->signin($values['user'], array_key_exists('remember', $values) ? $values['remember'] : false);
                
                
                $products_cart = unserialize($this->getUser()->getAttribute('products_to_cart'));
                $products_db_cart = unserialize($this->getUser()->getGuardUser()->get("cart"));
                foreach ($products_db_cart as $product_db_id => $product_db) {
                    if (@!is_array($products_cart[$product_db_id])) {
                        $products_cart[$product_db_id] = $product_db;
                    }
                }

                $this->getUser()->setAttribute('products_to_cart', serialize($products_cart));

                $GuardUser = $this->getUser()->getGuardUser();
                $GuardUser->set("cart", $this->getUser()->getAttribute('products_to_cart'));
                $GuardUser->save();
                
                
                
                //exit;
                // always redirect to a URL set in app.yml
                // or to the referer
                // or to the homepage
                $signinUrl = sfConfig::get('app_sf_guard_plugin_success_signin_url', $user->getReferer($request->getReferer()));
                if($signinUrl == "https://onona.ru/guard/login"){
                    $signinUrl = $user->getAttribute('returnURL');
                }

                $this->products_old = $this->getUser()->getAttribute('products_to_cart');
                $this->products_old = $this->products_old != '' ? unserialize($this->products_old) : '';
                return $this->redirect('' != $signinUrl ? $signinUrl : '/sexshop');

                return $this->redirect('/sexshop');

                return $this->redirect('' != $user->getAttribute('returnURL') ? $user->getAttribute('returnURL') : '@homepage');
                return $this->redirect('' != $signinUrl ? $signinUrl : '@homepage');
                //print_r($this->products_old); exit;

                if ($request->getParameter('cartEnter'))
                    return $this->redirect('/register');

                if (is_array($this->products_old)) {
                    return $this->redirect('/cart');
                } else {
                    return $this->redirect('' != $signinUrl ? $signinUrl : '@homepage');
                }

                //return $this->redirect('' != $signinUrl ? $signinUrl : '@homepage');
            } else {
                if ($this->getUser()->getAttribute('entercount') == "") {
                    $this->getUser()->setAttribute("entercount", 1);
                } else {
                    $this->getUser()->setAttribute("entercount", $user->getAttribute('entercount') + 1);
                }
                $this->getUser()->setAttribute("enterlasttime", time());
                $this->countExist = 5 - $user->getAttribute('entercount');
                if ($this->getUser()->getAttribute('entercount') > 5) {
                    return $this->redirect('/forgotpassword');
                }
            }
        } else {
            if ($request->isXmlHttpRequest()) {
                $this->getResponse()->setHeaderOnly(true);
                $this->getResponse()->setStatusCode(401);

                return sfView::NONE;
            }

            // if we have been forwarded, then the referer is the current URL
            // if not, this is the referer of the current request
            $user->setReferer($this->getContext()->getActionStack()->getSize() > 1 ? $request->getUri() : $request->getReferer());

            $module = sfConfig::get('sf_login_module');
            if ($this->getModuleName() != $module) {
                return $this->redirect($module . '/' . sfConfig::get('sf_login_action'));
            }

            $this->getResponse()->setStatusCode(401);
        }
    }

    public function executeSignout($request) {
        $user = $this->getUser();
        $signinUrl = sfConfig::get('app_sf_guard_plugin_success_signin_url', $user->getReferer($request->getReferer()));


        $this->getUser()->signOut();
        
        return $this->redirect('' != $signinUrl ? $signinUrl : '/sexshop');

        $signoutUrl = sfConfig::get('app_sf_guard_plugin_success_signout_url', $request->getReferer());

        //$this->redirect('' != $signoutUrl ? $signoutUrl : '@homepage');

        $this->redirect('@homepage');
    }

    public function executeSecure($request) {
        $this->getResponse()->setStatusCode(403);
    }

    public function executePassword($request) {
        throw new sfException('This method is not yet implemented.');
    }

}
