<?php namespace Drupal\Laravel;
use Symfony\Component\HttpFoundation\Cookie;
use Illuminate\Cookie\CookieJar;

class CookieExtra extends CookieJar
{
  /**
   * Override create a new cookie instance.
   *
   * @param  string  $name
   * @param  string  $value
   * @param  int     $minutes
   * @param  string  $path
   * @param  string  $domain
   * @param  bool    $secure
   * @param  bool    $httpOnly
   * @return \Symfony\Component\HttpFoundation\Cookie
   */
  public function make($name, $value, $minutes = 0, $path = null, $domain = null, $secure = false, $httpOnly = true)
  {
    list($path, $domain) = $this->getPathAndDomain($path, $domain);

    $time = ($minutes == 0) ? 0 : time() + ($minutes * 60);

    setcookie($name, $value, $time, $path, $domain, $secure, $httpOnly);
    if($time < 0 && isset($_COOKIE[$name])){
      unset($_COOKIE[$name]);
    }

    return new Cookie($name, $value, $time, $path, $domain, $secure, $httpOnly);
  }
}
