<?php
class TestQuestionAnswerValidatorSchema extends sfValidatorSchema
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
        $errorSchemaLocal->addError(new sfValidatorError($this, 'required'), 'balls');
      }
 
      // поле balls заполнено, answer - нет
      if ($value['balls'] && !$value['answer'])
      {
        $errorSchemaLocal->addError(new sfValidatorError($this, 'required'), 'answer');
      }
 
      // поля balls и answer не заполнены, удаляем пустые значения
      if (!$value['answer'] && !$value['balls'])
      {
        unset($values['Answer'][$key]);
      }
 
      // в этой внедрённой форме есть некоторые ошибки
      if (count($errorSchemaLocal))
      {
        $errorSchema->addError($errorSchemaLocal, (string) $key);
      }
    }
    
    foreach($values['new_Answer'] as $key => $value)
    {
      $errorSchemaLocal = new sfValidatorErrorSchema($this);
 
      // поле answer заполнено, balls - нет
      if (@$value['answer'] && (@$value['balls']=='' and @$value['balls']!='0'))
      {
        $errorSchemaLocal->addError(new sfValidatorError($this, 'required'), 'balls');
      }
 
      // поле balls заполнено, answer - нет
      if (@$value['balls'] && @!$value['answer'])
      {
        $errorSchemaLocal->addError(new sfValidatorError($this, 'required'), 'answer');
      }
 
      // поля balls и answer не заполнены, удаляем пустые значения
      if (@!$value['answer'] && @!$value['balls'])
      {
        unset($values['new_Answer'][$key]);
      }
 
      // в этой внедрённой форме есть некоторые ошибки
      if (count($errorSchemaLocal))
      {
        $errorSchema->addError($errorSchemaLocal, (string) $key);
      }
    }
    // передаём ошибку в главную форму
    if (count($errorSchema))
    {
      throw new sfValidatorErrorSchema($this, $errorSchema);
    }
 //print_r($values);exit;
    return $values;
  }
}
?>
