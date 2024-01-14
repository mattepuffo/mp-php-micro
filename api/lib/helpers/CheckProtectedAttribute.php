<?php

namespace helpers;

#[Attribute]
class CheckProtectedAttribute {
  private string $isProtected;

  public function __construct(string $isProtected) {
    $this->isProtected = $isProtected;
  }
}