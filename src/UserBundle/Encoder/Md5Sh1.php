<?php
namespace UserBundle\Encoder;


class Md5Sh1 implements \Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface
{
    public function encodePassword($raw, $salt)
    {
//        var_dump($raw);
//        die;
        return sha1(md5($raw) . $salt);
    }

    public function isPasswordValid($encoded, $raw, $salt)
    {
//        var_dump($encoded);
//        die;
        return $this->encodePassword($raw, $salt) === $encoded;
    }

}