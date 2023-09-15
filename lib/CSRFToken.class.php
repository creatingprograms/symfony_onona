<?php

class CSRFToken {

    const sf_csrf_secret = 'sf_csrf_secret';

    protected $value = '';

    public function __construct(
        $owner,
        $secret = ''
    ) {
        if (!$secret) {
           $secret = sfConfig::get(self::sf_csrf_secret);
        }

        if ($secret) {
           $this->value = md5(((string) $secret).session_id().get_class($owner));
        }
    }

    public function getValue() {
        return $this->value;
    }

    public function __toString() {
        return $this->getValue();
    }

    public function isValid($value) {
        return ((string)$value) === $this->value;
    }

    public function isValidKey($key, array $from) {
        return isset($from[$key]) ? $this->isValid($from[$key]) : false;
    }
}
