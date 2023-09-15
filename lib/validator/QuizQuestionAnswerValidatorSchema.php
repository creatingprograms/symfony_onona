<?php
class QuizQuestionAnswerValidatorSchema extends sfValidatorSchema
{
  protected function configure($options = array(), $messages = array())
  {
    $this->addMessage('answer', 'Поле ответ обязательно');
    $this->addMessage('balls', 'Поле баллов обязательно');
  }

  protected function doClean($values)
  {
    $errorSchema = new sfValidatorErrorSchema($this);
 //print_r($values);exit;
    foreach($values['Answer'] as $key => $value)
    {
      $errorSchemaLocal = new sfValidatorErrorSchema($this);

      // поле answer заполнено, balls - нет
      if ($value['answer'] && ($value['balls']=='' and $value['balls']!='0'))
      {
        $this->debug('поле answer заполнено, balls - нет', [$value]);
        $errorSchemaLocal->addError(new sfValidatorError($this, 'required'), 'balls');
      }

      // поле balls заполнено, answer - нет
      if ($value['balls'] && !$value['answer'])
      {
        $this->debug('поле balls заполнено, answer - нет', [$value]);
        $errorSchemaLocal->addError(new sfValidatorError($this, 'required'), 'answer');
      }

      // поля balls и answer не заполнены, удаляем пустые значения
      if (!$value['answer'] && !$value['balls'])
      {
        $this->debug('поля balls и answer не заполнены, удаляем пустые значения', [$value]);
        unset($values['Answer'][$key]);
      }

      // в этой внедрённой форме есть некоторые ошибки
      if (count($errorSchemaLocal))
      {
        $this->debug('в этой внедрённой форме есть некоторые ошибки', [$value]);
        $errorSchema->addError($errorSchemaLocal, (string) $key);
      }
    }

    foreach($values['new_Answer'] as $key => $value)
    {
      $errorSchemaLocal = new sfValidatorErrorSchema($this);

      // поле answer заполнено, balls - нет
      if (@$value['answer'] && (@$value['balls']=='' and @$value['balls']!='0'))
      {
        $this->debug('new_Answer: поле answer заполнено, balls - нет', [$value]);
        $errorSchemaLocal->addError(new sfValidatorError($this, 'required'), 'balls');
      }

      // поле balls заполнено, answer - нет
      if (@$value['balls'] && @!$value['answer'])
      {
        $this->debug('new_Answer: поле balls заполнено, answer - нет', [$value]);
        $errorSchemaLocal->addError(new sfValidatorError($this, 'required'), 'answer');
      }

      // поля balls и answer не заполнены, удаляем пустые значения
      if (@!$value['answer'] && @!$value['balls'])
      {
        $this->debug('new_Answer: поля balls и answer не заполнены, удаляем пустые значения', [$value]);
        unset($values['new_Answer'][$key]);
      }

      // в этой внедрённой форме есть некоторые ошибки
      if (count($errorSchemaLocal))
      {
        $this->debug('new_Answer: в этой внедрённой форме есть некоторые ошибки', [$value]);
        $errorSchema->addError($errorSchemaLocal, (string) $key);
      }
    }
    // if($values['number']!=1) $this->debug('validator', [$values, count($errorSchema)]);
    // передаём ошибку в главную форму
    if (count($errorSchema))
    {
      $this->debug('$errorSchema', [$value]);
      throw new sfValidatorErrorSchema($this, $errorSchema);
    }
   //print_r($values);exit;
    return $values;
  }
  private function debug($name, $data){
    // header('Content-type: text/html; charset=utf-8');
    // die($name.'<pre>'.print_r($data, true).'</pre>');
  }
}
?>
