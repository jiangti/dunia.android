<?php
/**
 *
 * 'id' => string '528952530' (length=9)
 * 'name' => string 'Jiangti Wan-Leong' (length=17)
 * 'first_name' => string 'Jiangti' (length=7)
 * 'last_name' => string 'Wan-Leong' (length=9)
 * 'link' => string 'http://www.facebook.com/jiangti' (length=31)
 * 'username' => string 'jiangti' (length=7)
 * 'birthday' => string '04/05/1981' (length=10)
 * 'location' =>
 *   array
 *     'id' => string '110884905606108' (length=15)
 *     'name' => string 'Sydney, Australia' (length=17)
 * 'gender' => string 'male' (length=4)
 * 'email' => string 'jiangti.wan.leong@gmail.com' (length=27)
 * 'timezone' => int 10
 * 'locale' => string 'en_US' (length=5)
 * 'verified' => boolean true
 * 'updated_time' => string '2011-05-28T14:16:14+0000' (length=24)
 *
 */
class Aw_Service_Fb_User {
    public function setFromArray($array) {
        $shift = array(
            'firstName' => 'first_name',
            'lastName' => 'last_name',
        );

        foreach ($shift as $index => $item) {
            $array[$index] = $array[$item];
            unset($array[$item]);
        }

        foreach ($array as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }

    public function __toString() {
        return $this->name;
    }
}