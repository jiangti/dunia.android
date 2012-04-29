<?php

/*
 * Copyright 2012 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * @group aws
 */
final class PhutilAWSException extends Exception {

  private $httpStatus;
  private $requestID;

  public function __construct($http_status, array $params) {
    $this->httpStatus = $http_status;
    $this->requestID = idx($params, 'RequestID');

    $this->params = $params;

    $desc = array();
    $desc[] = 'AWS Request Failed';
    $desc[] = 'HTTP Status Code: '.$http_status;

    if ($this->requestID) {
      $desc[] = 'AWS Request ID: '.$this->requestID;
      $errors = idx($params, 'Errors');
      if ($errors) {
        $desc[] = 'AWS Errors:';
        foreach ($errors as $error) {
          list($code, $message) = $error;
          $desc[] = "    - {$code}: {$message}\n";
        }
      }
    } else {
      $desc[] = 'Response Body: '.idx($params, 'body');
    }

    $desc = implode("\n", $desc);

    parent::__construct($desc);
  }

  public function getRequestID() {
    return $this->requestID;
  }

  public function getHTTPStatus() {
    return $this->httpStatus;
  }

}

