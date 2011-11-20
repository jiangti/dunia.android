<?php
class DataController extends ControllerAbstract {
    public function oneAction() {

        $pubTable = new Model_DbTable_Pub();

        foreach ($rows as $index => $row) {
            $pub = $pubTable->createRow();
            $pub->save();
            $pub->setAddress($address);

            $pub->addPromo($promo);
        }
    }
}