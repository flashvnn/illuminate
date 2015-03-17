<?php
/**
 * Created by PhpStorm.
 * User: thaohuynh
 * Date: 3/14/15
 * Time: 19:12
 */

class IlluminateMailSystem  extends DefaultMailSystem{
  /**
   * @inheritdoc
   */
  public function format(array $message) {
    // Join the body array into one string.
    $message['body'] = implode("\n\n", $message['body']);
    $message['body'] = nl2br($message['body']);
    return $message;
  }

  /**
   * @inheritdoc
   */
  public function mail(array $mail) {
    $mail_default_template = "emails.default";
    $mail_template = 'emails.' . $mail['id'];
    if (View::exists($mail_template)) {
      $mail_default_template = $mail_template;
    }
    $mail_rs = false;
    try
    {
      Mail::send($mail_default_template, array('mail' => $mail), function ($message) use ($mail) {
        /** @var $message \Illuminate\Mail\Message */
        $message->from($mail['from'])->to($mail['to'])->subject($mail['subject']);
      });
      $mail_rs = true;
    }
    catch (\Exception $e)
    {
      watchdog('mail', $e->getMessage());
    }

    return $mail_rs;
  }
}